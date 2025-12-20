<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('events')) {
            if (! Schema::hasColumn('events', 'category')) {
                Schema::table('events', function (Blueprint $table) {
                    $table->string('category')->nullable();
                });
            }
            if (! Schema::hasColumn('events', 'min_age')) {
                Schema::table('events', function (Blueprint $table) {
                    $table->integer('min_age')->nullable();
                });
            }
            if (! Schema::hasColumn('events', 'days_count')) {
                Schema::table('events', function (Blueprint $table) {
                    $table->integer('days_count')->nullable();
                });
            }
            if (! Schema::hasColumn('events', 'schedule')) {
                Schema::table('events', function (Blueprint $table) {
                    $table->json('schedule')->nullable();
                });
            }
            if (! Schema::hasColumn('events', 'arrival_info')) {
                Schema::table('events', function (Blueprint $table) {
                    $table->text('arrival_info')->nullable();
                });
            }
            if (! Schema::hasColumn('events', 'map_embed_url')) {
                Schema::table('events', function (Blueprint $table) {
                    $table->string('map_embed_url')->nullable();
                });
            }
            if (! Schema::hasColumn('events', 'parceling_enabled')) {
                Schema::table('events', function (Blueprint $table) {
                    $table->boolean('parceling_enabled')->default(false);
                });
            }
            if (! Schema::hasColumn('events', 'parceling_max')) {
                Schema::table('events', function (Blueprint $table) {
                    $table->integer('parceling_max')->nullable();
                });
            }
            if (! Schema::hasColumn('events', 'coupons_enabled')) {
                Schema::table('events', function (Blueprint $table) {
                    $table->boolean('coupons_enabled')->default(false);
                });
            }
            if (! Schema::hasColumn('events', 'extra_services')) {
                Schema::table('events', function (Blueprint $table) {
                    $table->json('extra_services')->nullable();
                });
            }
            if (! Schema::hasColumn('events', 'terms')) {
                Schema::table('events', function (Blueprint $table) {
                    $table->longText('terms')->nullable();
                });
            }
            if (! Schema::hasColumn('events', 'rules')) {
                Schema::table('events', function (Blueprint $table) {
                    $table->longText('rules')->nullable();
                });
            }
        }
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'category', 'min_age', 'days_count', 'schedule', 'arrival_info', 'map_embed_url',
                'parceling_enabled', 'parceling_max', 'coupons_enabled', 'extra_services', 'terms', 'rules',
            ]);
        });
    }
};
