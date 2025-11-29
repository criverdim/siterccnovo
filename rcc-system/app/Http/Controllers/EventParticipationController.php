<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventParticipation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventParticipationController extends Controller
{
    public function participate(Request $request, Event $event)
    {
        $data = $request->validate([
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'max:255'],
            'cpf' => ['nullable', 'string', 'max:14'],
        ]);

        $user = null;
        if ($data['user_id'] ?? null) {
            $user = User::find($data['user_id']);
        }

        if (! $user) {
            $user = User::query()
                ->where(function ($q) use ($data) {
                    $q->when($data['cpf'] ?? null, fn ($q, $cpf) => $q->orWhere('cpf', $cpf))
                        ->when($data['email'] ?? null, fn ($q, $email) => $q->orWhere('email', $email))
                        ->when($data['phone'] ?? null, fn ($q, $phone) => $q->orWhere('phone', $phone))
                        ->when($data['whatsapp'] ?? null, fn ($q, $wa) => $q->orWhere('whatsapp', $wa));
                })
                ->first();
        }

        if (! $user) {
            $user = User::create([
                'name' => $data['name'] ?? 'Participante',
                'email' => $data['email'] ?? Str::uuid().'@local',
                'phone' => $data['phone'] ?? '',
                'whatsapp' => $data['whatsapp'] ?? '',
                'password' => bcrypt(Str::random(12)),
                'profile_completed_at' => null,
            ]);
        }

        $participation = EventParticipation::firstOrCreate(
            ['user_id' => $user->id, 'event_id' => $event->id],
            [
                'payment_status' => $event->is_paid ? 'pending' : 'approved',
                'ticket_uuid' => $event->generates_ticket ? (string) Str::uuid() : null,
            ]
        );

        return response()->json([
            'status' => 'ok',
            'participation_id' => $participation->id,
            'payment_required' => $event->is_paid,
        ]);
    }
}
