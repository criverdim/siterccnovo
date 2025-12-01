<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('fiel')->after('status');
            }
            // Índice para CPF
            try { $table->index('cpf'); } catch (\Throwable $e) {}
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
            try { $table->dropIndex('users_cpf_index'); } catch (\Throwable $e) {}
            // não remove unique de email por segurança; se necessário:
            // $table->dropUnique(['email']);
        });
    }
};
