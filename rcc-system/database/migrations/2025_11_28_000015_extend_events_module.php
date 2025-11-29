<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('category')->nullable()->after('name');
            $table->integer('min_age')->nullable()->after('price');
            $table->integer('days_count')->nullable()->after('end_date');
            $table->json('schedule')->nullable()->after('days_count');
            $table->text('arrival_info')->nullable()->after('location');
            $table->string('map_embed_url')->nullable()->after('arrival_info');
            $table->boolean('parceling_enabled')->default(false)->after('allows_online_payment');
            $table->integer('parceling_max')->nullable()->after('parceling_enabled');
            $table->boolean('coupons_enabled')->default(false)->after('parceling_max');
            $table->json('extra_services')->nullable()->after('coupons_enabled');
            $table->longText('terms')->nullable()->after('extra_services');
            $table->longText('rules')->nullable()->after('terms');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'category','min_age','days_count','schedule','arrival_info','map_embed_url',
                'parceling_enabled','parceling_max','coupons_enabled','extra_services','terms','rules'
            ]);
        });
    }
};

