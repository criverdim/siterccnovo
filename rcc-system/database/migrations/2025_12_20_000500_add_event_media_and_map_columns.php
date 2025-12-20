<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                if (! Schema::hasColumn('events', 'featured_image')) {
                    $table->string('featured_image')->nullable();
                }
                if (! Schema::hasColumn('events', 'gallery_images')) {
                    $table->json('gallery_images')->nullable();
                }
                if (! Schema::hasColumn('events', 'map_embed_url')) {
                    $table->string('map_embed_url')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                if (Schema::hasColumn('events', 'featured_image')) {
                    $table->dropColumn('featured_image');
                }
                if (Schema::hasColumn('events', 'gallery_images')) {
                    $table->dropColumn('gallery_images');
                }
                if (Schema::hasColumn('events', 'map_embed_url')) {
                    $table->dropColumn('map_embed_url');
                }
            });
        }
    }
};
