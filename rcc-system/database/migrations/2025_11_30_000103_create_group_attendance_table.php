<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('group_attendance')) {
            Schema::create('group_attendance', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('group_id');
                $table->date('date');
                $table->unsignedBigInteger('created_by')->nullable();
                $table->string('source')->nullable();
                $table->timestamps();
                $table->index(['user_id', 'group_id', 'date']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('group_attendance');
    }
};
