<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ListSessions extends Command
{
    protected $signature = 'rcc:list-sessions {--limit=10}';

    protected $description = 'Lista registros recentes da tabela sessions para diagnóstico';

    public function handle(): int
    {
        $limit = (int) $this->option('limit');
        $count = DB::table('sessions')->count();
        $this->line('Total de sessões: '.$count);
        $rows = DB::table('sessions')->orderByDesc('last_activity')->limit($limit)->get();
        foreach ($rows as $row) {
            $this->line(sprintf('id=%s user_id=%s last=%s ip=%s', $row->id, $row->user_id ?? '-', $row->last_activity, $row->ip_address ?? '-'));
        }

        return self::SUCCESS;
    }
}
