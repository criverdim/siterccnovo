<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('events')) {
            try {
                DB::statement('ALTER TABLE `events` MODIFY `start_time` TIME NULL');
            } catch (\Throwable $e) {
                try {
                    DB::statement('ALTER TABLE events MODIFY start_time TIME NULL');
                } catch (\Throwable $e2) {
                    Schema::table('events', function (Blueprint $table) {
                        $table->time('start_time')->nullable()->change();
                    });
                }
            }
            try {
                DB::statement('ALTER TABLE `events` MODIFY `end_time` TIME NULL');
            } catch (\Throwable $e) {
                try {
                    DB::statement('ALTER TABLE events MODIFY end_time TIME NULL');
                } catch (\Throwable $e2) {
                    Schema::table('events', function (Blueprint $table) {
                        $table->time('end_time')->nullable()->change();
                    });
                }
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('events')) {
            try {
                DB::statement('ALTER TABLE `events` MODIFY `start_time` TIME NOT NULL');
            } catch (\Throwable $e) {
                try {
                    DB::statement('ALTER TABLE events MODIFY start_time TIME NOT NULL');
                } catch (\Throwable $e2) {
                    Schema::table('events', function (Blueprint $table) {
                        $table->time('start_time')->nullable(false)->change();
                    });
                }
            }
        }
    }
};
