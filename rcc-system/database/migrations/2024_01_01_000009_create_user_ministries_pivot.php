<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('user_ministries')) {
            return;
        }
        Schema::create('user_ministries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('ministry_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->unique(['user_id', 'ministry_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_ministries');
    }
};
