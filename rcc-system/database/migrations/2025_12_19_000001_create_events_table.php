<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('events')) {
            Schema::create('events', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('category')->nullable();
                $table->text('description')->nullable();
                $table->text('short_description')->nullable();
                $table->datetime('start_date');
                $table->datetime('end_date');
                $table->time('start_time')->nullable();
                $table->time('end_time')->nullable();
                $table->string('location');
                $table->string('address')->nullable();
                $table->decimal('price', 10, 2);
                $table->integer('capacity');
                $table->integer('tickets_sold')->default(0);
                $table->string('status')->default('active');
                $table->boolean('is_active')->default(false);
                $table->boolean('show_on_homepage')->default(false);
                $table->integer('days_count')->nullable();
                $table->integer('min_age')->nullable();
                $table->string('featured_image')->nullable();
                $table->json('gallery_images')->nullable();
                $table->json('photos')->nullable();
                $table->json('organizers')->nullable();
                $table->json('schedule')->nullable();
                $table->json('additional_info')->nullable();
                $table->boolean('is_featured')->default(false);
                $table->boolean('is_paid')->default(true);
                $table->boolean('parceling_enabled')->default(false);
                $table->integer('parceling_max')->default(1);
                $table->boolean('coupons_enabled')->default(false);
                $table->boolean('has_coffee')->default(false);
                $table->boolean('has_lunch')->default(false);
                $table->boolean('generates_ticket')->default(true);
                $table->boolean('allows_online_payment')->default(true);
                $table->text('arrival_info')->nullable();
                $table->text('terms')->nullable();
                $table->text('rules')->nullable();
                $table->string('map_embed_url')->nullable();
                $table->string('mercado_pago_preference_id')->nullable();
                $table->timestamps();

                $table->index('status');
                $table->index('start_date');
                $table->index('end_date');
                $table->index('is_featured');
                $table->index('is_active');
                $table->index('show_on_homepage');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
