<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('user_groups')) {
            return;
        }
        Schema::create('user_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('group_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->unique(['user_id', 'group_id']);
        });

        // Opcional: migrar dados existentes de users.group_id para user_groups
        if (Schema::hasColumn('users', 'group_id')) {
            $driver = config('database.default');
            $createdExpr = $driver === 'sqlite' ? DB::raw("datetime('now')") : DB::raw('CURRENT_TIMESTAMP');
            $updatedExpr = $createdExpr;
            DB::table('user_groups')->insertUsing([
                'user_id', 'group_id', 'created_at', 'updated_at',
            ], DB::table('users')
                ->select(['id as user_id', 'group_id', $createdExpr, $updatedExpr])
                ->whereNotNull('group_id'));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_groups');
    }
};
