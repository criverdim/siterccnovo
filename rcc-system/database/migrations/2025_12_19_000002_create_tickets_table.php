<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tickets')) {
            Schema::create('tickets', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('event_id')->constrained()->onDelete('cascade');
                $table->unsignedBigInteger('payment_id')->nullable();
                $table->string('qr_code')->unique();
                $table->string('ticket_code')->unique();
                $table->string('status')->default('active'); // active, used, cancelled
                $table->datetime('used_at')->nullable();
                $table->string('pdf_path')->nullable();
                $table->json('additional_data')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'event_id']);
                $table->index('qr_code');
                $table->index('ticket_code');
                $table->index('status');
                $table->index('payment_id');
            });
        } else {
            Schema::table('tickets', function (Blueprint $table) {
                if (! Schema::hasColumn('tickets', 'payment_id')) {
                    $table->unsignedBigInteger('payment_id')->nullable();
                    $table->index('payment_id');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
