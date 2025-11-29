<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->string('cover_bg_color')->nullable()->after('cover_photo');
            $table->string('cover_object_position')->nullable()->after('cover_bg_color');
        });
    }

    public function down(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn(['cover_bg_color','cover_object_position']);
        });
    }
};

