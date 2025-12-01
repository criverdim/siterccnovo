<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMissingAttendanceMessages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $template = optional(Setting::where('key','templates')->first())->value['template_saudades'] ?? 'Sentimos sua falta, {{name}}!';
        $cut = now()->subDays(60)->toDateString();
        $users = User::whereHas('groupAttendance')->get();
        foreach ($users as $u) {
            $last = optional($u->groupAttendance()->latest('date')->first())->date;
            if (! $last || $last->toDateString() < $cut) {
                $msg = str_replace('{{name}}', $u->name, $template);
                \Log::info('[MissingAttendance] '.$u->id.' '.$msg);
            }
        }
    }
}

