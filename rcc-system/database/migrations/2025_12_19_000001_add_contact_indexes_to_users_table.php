<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            try {
                $table->index('phone');
            } catch (\Throwable $e) {
            }
            try {
                $table->index('whatsapp');
            } catch (\Throwable $e) {
            }
            try {
                $table->index('birth_date');
            } catch (\Throwable $e) {
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            try {
                $table->dropIndex('users_phone_index');
            } catch (\Throwable $e) {
            }
            try {
                $table->dropIndex('users_whatsapp_index');
            } catch (\Throwable $e) {
            }
            try {
                $table->dropIndex('users_birth_date_index');
            } catch (\Throwable $e) {
            }
        });
    }
};
