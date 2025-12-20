<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateSqliteToMysql extends Command
{
    protected $signature = 'db:migrate-sqlite-to-mysql {--tables=*} {--dry-run}';

    protected $description = 'Migra dados do SQLite local (database/database.sqlite) para MySQL';

    public function handle(): int
    {
        $sqlitePath = database_path('database.sqlite');
        if (! file_exists($sqlitePath)) {
            $this->error('Arquivo SQLite não encontrado em: '.$sqlitePath);

            return 1;
        }

        config(['database.connections.sqlite.database' => $sqlitePath]);

        $default = config('database.default');
        if ($default !== 'mysql') {
            $this->warn('Conexão padrão não é mysql: '.$default);
        }

        $tablesOpt = (array) $this->option('tables');
        if (! empty($tablesOpt)) {
            $tables = collect($tablesOpt)->filter()->values()->all();
        } else {
            $tables = collect(DB::connection('sqlite')->select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'"))
                ->pluck('name')
                ->filter(function ($t) {
                    return ! in_array($t, ['migrations']);
                })
                ->values()
                ->all();
        }

        $dryRun = (bool) $this->option('dry-run');
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $summary = [];
        foreach ($tables as $table) {
            if (! Schema::hasTable($table)) {
                $this->line('Ignorando tabela ausente no MySQL: '.$table);

                continue;
            }

            $srcCols = collect(DB::connection('sqlite')->select("PRAGMA table_info($table)"))->pluck('name')->all();
            $destCols = Schema::getColumnListing($table);
            $cols = array_values(array_intersect($srcCols, $destCols));
            if (empty($cols)) {
                $this->line('Sem colunas em comum, ignorando: '.$table);

                continue;
            }

            $count = 0;
            $query = DB::connection('sqlite')->table($table);
            if (in_array('id', $srcCols, true)) {
                $query = $query->orderBy('id');
            }
            foreach ($query->cursor() as $row) {
                $data = [];
                foreach ($cols as $c) {
                    $data[$c] = $row->$c;
                }
                if ($dryRun) {
                    $count++;

                    continue;
                }
                if (array_key_exists('id', $data)) {
                    DB::table($table)->updateOrInsert(['id' => $data['id']], $data);
                } elseif (array_key_exists('key', $data)) {
                    DB::table($table)->updateOrInsert(['key' => $data['key']], $data);
                } else {
                    try {
                        DB::table($table)->insert($data);
                    } catch (\Throwable $e) {
                        $pk = null;
                        if (Schema::hasColumn($table, 'email') && array_key_exists('email', $data)) {
                            $pk = ['email' => $data['email']];
                        }
                        if ($pk) {
                            DB::table($table)->updateOrInsert($pk, $data);
                        }
                    }
                }
                $count++;
            }

            $summary[$table] = $count;
            $this->info($table.': '.$count);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        $this->info('Migração concluída');
        $this->line(json_encode($summary));

        return 0;
    }
}
