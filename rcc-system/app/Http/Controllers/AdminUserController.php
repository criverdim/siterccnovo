<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserActivity;
use App\Models\UserMessage;
use App\Models\UserPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['group', 'groups', 'activePhoto', 'activities', 'ministries', 'coordinatorMinistry'])
            ->when($request->search, function ($q, $search) {
                $q->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($request->status, function ($q, $status) {
                $q->where('status', $status);
            })
            ->when($request->group_id, function ($q, $groupId) {
                $q->where('group_id', $groupId);
            })
            ->when($request->role, function ($q, $role) {
                $q->where('role', $role);
            })
            ->when($request->has('can_access_admin'), function ($q) use ($request) {
                $q->where('can_access_admin', $request->boolean('can_access_admin'));
            })
            ->when($request->has('is_servo'), function ($q) use ($request) {
                $q->where('is_servo', $request->boolean('is_servo'));
            });

        $users = $query->paginate($request->per_page ?? 12);

        return response()->json([
            'users' => collect($users->items())->map(function ($u) {
                $arr = $u->toArray();
                $arr['group'] = $u->group ? ['id' => $u->group->id, 'name' => $u->group->name, 'color_hex' => $u->group->color_hex] : null;
                $arr['groups'] = $u->groups?->map(fn ($g) => ['id' => $g->id, 'name' => $g->name, 'color_hex' => $g->color_hex])->values()->all() ?? [];

                return $arr;
            })->all(),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
                'from' => $users->firstItem(),
                'to' => $users->lastItem(),
            ],
        ]);
    }

    public function show($id)
    {
        try {
            $user = User::with([
                'group',
                'groups',
                'activePhoto',
                'photos',
                'activities' => function ($query) {
                    $query->recent(30)->orderBy('created_at', 'desc');
                },
                'messages' => function ($query) {
                    $query->where('created_at', '>=', now()->subDays(30))
                        ->orderBy('created_at', 'desc');
                },
                'ministries',
                'coordinatorMinistry',
            ])->findOrFail($id);

            $arr = $user->toArray();
            $arr['group'] = $user->group ? ['id' => $user->group->id, 'name' => $user->group->name, 'color_hex' => $user->group->color_hex] : null;
            $arr['groups'] = $user->groups?->map(fn ($g) => ['id' => $g->id, 'name' => $g->name, 'color_hex' => $g->color_hex])->values()->all() ?? [];

            return response()->json([
                'user' => $arr,
            ]);
        } catch (\Throwable $e) {
            logger()->error('admin.user.show_failed', ['id' => $id, 'message' => $e->getMessage()]);

            return response()->json(['message' => 'Erro ao carregar usuário'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'sometimes|string|max:20',
            'whatsapp' => 'sometimes|string|max:20',
            'birth_date' => 'sometimes|date',
            'cpf' => 'sometimes|string|max:14',
            'cep' => 'sometimes|string|max:9',
            'address' => 'sometimes|string|max:255',
            'number' => 'sometimes|string|max:10',
            'complement' => 'nullable|string|max:255',
            'district' => 'sometimes|string|max:100',
            'city' => 'sometimes|string|max:100',
            'state' => 'sometimes|string|max:2',
            'gender' => 'sometimes|string|in:male,female,other',
            'group_id' => 'sometimes|exists:groups,id',
            'status' => 'sometimes|string|in:active,inactive,pending',
            'role' => 'sometimes|string|in:user,admin,leader,servant',
            'is_servo' => 'sometimes|boolean',
            'can_access_admin' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user->update($request->all());

        UserActivity::create([
            'user_id' => $user->id,
            'activity_type' => 'profile_updated',
            'details' => [
                'updated_by' => auth()->id(),
                'updated_fields' => array_keys($request->all()),
            ],
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user->fresh(['group', 'groups', 'activePhoto']),
        ]);
    }

    public function updateCoordinator(Request $request, $id)
    {
        $actor = $request->user();
        if (! $actor || ! ($actor->is_master_admin || $actor->role === 'admin')) {

            return response()->json(['message' => 'Forbidden'], 403);
        }
        $user = User::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'is_coordinator' => 'required|boolean',
            'coordinator_ministry_id' => 'nullable|exists:ministries,id',
        ]);
        if ($validator->fails()) {

            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }
        $isCoord = (bool) $request->boolean('is_coordinator');
        $ministryId = $request->input('coordinator_ministry_id');
        if ($isCoord && ! $ministryId) {
            return response()->json(['message' => 'Ministério obrigatório'], 422);
        }
        $user->is_coordinator = $isCoord;
        $user->coordinator_ministry_id = $isCoord ? $ministryId : null;
        $user->save();
        if ($isCoord && $ministryId) {
            $exists = $user->ministries()->where('ministries.id', $ministryId)->exists();
            if (! $exists) {
                $user->ministries()->attach($ministryId);
            }
        }
        UserActivity::create([
            'user_id' => $user->id,
            'activity_type' => 'status_changed',
            'details' => [
                'field' => 'coordinator',
                'is_coordinator' => $isCoord,
                'coordinator_ministry_id' => $user->coordinator_ministry_id,
                'changed_by' => $actor->id,
            ],
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'Coordinator updated',
            'user' => $user->fresh(['coordinatorMinistry']),
        ]);
    }

    public function sendMessage(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'message_type' => 'required|string|in:email,notification',
            'subject' => 'required_if:message_type,email|string|max:255',
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $message = UserMessage::create([
            'user_id' => $user->id,
            'sent_by' => auth()->id(),
            'message_type' => $request->message_type,
            'subject' => $request->subject,
            'content' => $request->content,
            'status' => 'pending',
        ]);

        UserActivity::create([
            'user_id' => $user->id,
            'activity_type' => 'message_sent',
            'details' => [
                'message_type' => $request->message_type,
                'subject' => $request->subject,
                'sent_by' => auth()->id(),
            ],
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'Message sent successfully',
            'user_message' => $message,
        ]);
    }

    public function bulkUpdateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'status' => 'required|string|in:active,inactive,pending',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::transaction(function () use ($request) {
            User::whereIn('id', $request->user_ids)->update(['status' => $request->status]);

            foreach ($request->user_ids as $userId) {
                UserActivity::create([
                    'user_id' => $userId,
                    'activity_type' => 'status_changed',
                    'details' => [
                        'old_status' => User::find($userId)->getOriginal('status'),
                        'new_status' => $request->status,
                        'changed_by' => auth()->id(),
                    ],
                    'ip_address' => $request->ip(),
                ]);
            }
        });

        return response()->json([
            'message' => 'Users status updated successfully',
            'updated_count' => count($request->user_ids),
        ]);
    }

    public function uploadPhoto(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        if ($request->boolean('is_active', true)) {
            $user->photos()->update(['is_active' => false]);
        }

        $path = $request->file('photo')->store('user-photos/'.$user->id, 'public');

        $photo = UserPhoto::create([
            'user_id' => $user->id,
            'file_path' => $path,
            'file_name' => $request->file('photo')->getClientOriginalName(),
            'file_size' => $request->file('photo')->getSize(),
            'mime_type' => $request->file('photo')->getMimeType(),
            'is_active' => $request->boolean('is_active', true),
        ]);

        UserActivity::create([
            'user_id' => $user->id,
            'activity_type' => 'photo_uploaded',
            'details' => [
                'photo_id' => $photo->id,
                'file_name' => $photo->file_name,
                'file_size' => $photo->file_size,
                'uploaded_by' => auth()->id(),
            ],
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'Photo uploaded successfully',
            'photo' => $photo,
        ]);
    }
}
