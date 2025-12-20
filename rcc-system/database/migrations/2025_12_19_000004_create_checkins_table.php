<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checkins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
            $table->foreignId('validated_by')->constrained('users')->onDelete('cascade');
            $table->string('status')->default('valid'); // valid, invalid, duplicate
            $table->datetime('checkin_at')->useCurrent();
            $table->string('validation_method')->default('manual'); // manual, qr_code, manual_override
            $table->text('notes')->nullable();
            $table->json('additional_data')->nullable();
            $table->timestamps();

            $table->unique('ticket_id'); // Um ingresso só pode ser validado uma vez
            $table->index('validated_by');
            $table->index('checkin_at');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checkins');
    }
};
