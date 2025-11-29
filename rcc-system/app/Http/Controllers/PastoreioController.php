<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupAttendance;
use App\Models\GroupDraw;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PastoreioController extends Controller
{
    public function index()
    {
        $groups = Group::orderBy('name')->get();

        // Métricas gerais de Pastoreio com tolerância a falhas
        try {
            $totalAttendance = GroupAttendance::count();
            $now = now();
            $last30 = GroupAttendance::whereDate('date', '>=', $now->copy()->subDays(30))->count();
            $last60 = GroupAttendance::whereDate('date', '>=', $now->copy()->subDays(60))->count();
            $last90 = GroupAttendance::whereDate('date', '>=', $now->copy()->subDays(90))->count();

            // Ranking dos mais presentes (top 10)
            $ranking = GroupAttendance::selectRaw('user_id, COUNT(*) as total')
                ->groupBy('user_id')
                ->orderByDesc('total')
                ->with('user')
                ->limit(10)
                ->get();

            // Novos participantes (últimos 30 dias com presença)
            $newParticipantsCount = User::whereDate('created_at', '>=', $now->copy()->subDays(30))
                ->whereHas('groupAttendance', function ($q) use ($now) {
                    $q->whereDate('date', '>=', $now->copy()->subDays(30));
                })
                ->count();

            // Fieis em risco: tiveram presença no passado, mas nos últimos 60 dias <= 1 presença
            $atRiskCount = User::whereHas('groupAttendance')
                ->whereDoesntHave('groupAttendance', function ($q) use ($now) {
                    $q->whereDate('date', '>=', $now->copy()->subDays(60));
                })
                ->count();
        } catch (\Throwable $e) {
            report($e);
            $totalAttendance = 0;
            $last30 = 0;
            $last60 = 0;
            $last90 = 0;
            $ranking = collect();
            $newParticipantsCount = 0;
            $atRiskCount = 0;
        }

        return view('pastoreio.index', compact(
            'groups',
            'totalAttendance',
            'last30',
            'last60',
            'last90',
            'ranking',
            'newParticipantsCount',
            'atRiskCount'
        ));
    }

    public function search(Request $request)
    {
        $data = $request->validate([
            'query' => ['required', 'string', 'max:255'],
        ]);

        $q = $data['query'];
        $users = User::query()
            ->where(function ($builder) use ($q) {
                $builder->where('name', 'like', "%$q%")
                    ->orWhere('cpf', 'like', "%$q%")
                    ->orWhere('phone', 'like', "%$q%")
                    ->orWhere('whatsapp', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%");
            })
            ->limit(20)
            ->get(['id', 'name', 'email', 'phone', 'whatsapp', 'cpf']);

        return response()->json($users);
    }

    public function attendance(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'name' => ['nullable', 'string', 'max:255'],
            'cpf' => ['nullable', 'string', 'max:20'],
            'phone' => ['nullable', 'string', 'max:50'],
            'group_id' => ['required', 'integer', 'exists:groups,id'],
            'date' => ['required', 'date'],
        ]);

        $user = $data['user_id'] ? User::find($data['user_id']) : null;
        if (! $user) {
            $user = User::query()
                ->where(function ($q) use ($data) {
                    $q->when($data['cpf'] ?? null, fn ($q, $cpf) => $q->orWhere('cpf', $cpf))
                        ->when($data['phone'] ?? null, fn ($q, $phone) => $q->orWhere('phone', $phone))
                        ->when($data['name'] ?? null, fn ($q, $name) => $q->orWhere('name', 'like', "%$name%"));
                })
                ->first();
        }
        if (! $user) {
            $user = User::create([
                'name' => $data['name'] ?? 'Participante',
                'email' => Str::uuid().'@local',
                'phone' => $data['phone'] ?? '',
                'password' => bcrypt(Str::random(12)),
                'group_id' => $data['group_id'],
            ]);
        }

        $attendance = GroupAttendance::firstOrCreate(
            [
                'user_id' => $user->id,
                'group_id' => $data['group_id'],
                'date' => $data['date'],
            ],
            [
                'created_by' => auth()->id() ?? $user->id,
                'source' => 'manual',
            ]
        );

        return response()->json(['status' => 'ok', 'attendance_id' => $attendance->id]);
    }

    public function draw(Request $request)
    {
        $data = $request->validate([
            'group_id' => ['required', 'integer', 'exists:groups,id'],
            'date' => ['required', 'date'],
            'prize' => ['nullable', 'string', 'max:255'],
        ]);

        $attendances = GroupAttendance::query()
            ->where('group_id', $data['group_id'])
            ->whereDate('date', $data['date'])
            ->pluck('user_id');

        if ($attendances->isEmpty()) {
            return response()->json(['status' => 'empty']);
        }

        $seed = (string) random_int(PHP_INT_MIN, PHP_INT_MAX);
        mt_srand(abs(crc32($seed)));
        $winnerUserId = $attendances[mt_rand(0, count($attendances) - 1)];

        $draw = GroupDraw::create([
            'user_id' => $winnerUserId,
            'group_id' => $data['group_id'],
            'date' => $data['date'],
            'rng_seed' => $seed,
            'prize' => $data['prize'] ?? null,
        ]);

        return response()->json(['status' => 'ok', 'draw_id' => $draw->id, 'user_id' => $winnerUserId]);
    }
}
