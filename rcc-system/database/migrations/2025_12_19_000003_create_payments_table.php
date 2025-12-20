<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('mercado_pago_id')->unique()->nullable();
            $table->string('mercado_pago_preference_id')->unique()->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('BRL');
            $table->string('status')->default('pending'); // pending, approved, rejected, cancelled, refunded
            $table->json('mercado_pago_data')->nullable();
            $table->string('payment_method')->nullable();
            $table->datetime('paid_at')->nullable();
            $table->string('external_reference')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('event_id');
            $table->index('status');
            $table->index('mercado_pago_id');
            $table->index('mercado_pago_preference_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
