<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_participations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('payment_status')->default('pending'); // pending, approved, rejected, cancelled, refunded
            $table->string('payment_method')->nullable(); // pix, credit_card, boleto
            $table->string('mp_payment_id')->nullable();
            $table->json('mp_payload_raw')->nullable();
            $table->string('ticket_uuid')->unique()->nullable();
            $table->string('ticket_qr_hash')->unique()->nullable();
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'event_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_participations');
    }
};
