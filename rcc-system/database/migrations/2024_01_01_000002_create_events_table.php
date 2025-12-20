<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('photos')->nullable();
            $table->string('location');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->time('start_time');
            $table->time('end_time')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->decimal('price', 10, 2)->nullable();
            $table->boolean('has_coffee')->default(false);
            $table->boolean('has_lunch')->default(false);
            $table->boolean('generates_ticket')->default(true);
            $table->boolean('allows_online_payment')->default(true);
            $table->integer('capacity')->nullable();
            $table->boolean('show_on_homepage')->default(true);
            $table->boolean('is_active')->default(true);
            $table->string('featured_image')->nullable();
            $table->json('gallery_images')->nullable();
            $table->string('map_embed_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
