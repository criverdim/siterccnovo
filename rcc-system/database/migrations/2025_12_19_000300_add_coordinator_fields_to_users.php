<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'is_coordinator')) {
                $table->boolean('is_coordinator')->default(false)->index();
            }
            if (! Schema::hasColumn('users', 'coordinator_ministry_id')) {
                $table->foreignId('coordinator_ministry_id')->nullable()->constrained('ministries')->nullOnDelete()->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'coordinator_ministry_id')) {
                $table->dropConstrainedForeignId('coordinator_ministry_id');
            }
            if (Schema::hasColumn('users', 'is_coordinator')) {
                $table->dropColumn('is_coordinator');
            }
        });
    }
};
