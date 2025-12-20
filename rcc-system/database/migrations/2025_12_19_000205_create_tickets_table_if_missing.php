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
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('event_id');
                $table->unsignedBigInteger('payment_id');
                $table->string('qr_code')->nullable();
                $table->string('ticket_code')->unique();
                $table->string('status')->default('active');
                $table->timestamp('used_at')->nullable();
                $table->string('pdf_path')->nullable();
                $table->json('additional_data')->nullable();
                $table->timestamps();
                $table->index(['user_id', 'event_id']);
                $table->index('ticket_code');
                $table->index('status');
            });
            try {
                Schema::table('tickets', function (Blueprint $table) {
                    if (Schema::hasTable('users')) {
                        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                    }
                    if (Schema::hasTable('events')) {
                        $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
                    }
                    if (Schema::hasTable('payments')) {
                        $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
                    }
                });
            } catch (\Throwable $e) {
            }
        } else {
            try {
                Schema::table('tickets', function (Blueprint $table) {
                    if (Schema::hasTable('users')) {
                        try {
                            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                        } catch (\Throwable $e) {
                        }
                    }
                    if (Schema::hasTable('events')) {
                        try {
                            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
                        } catch (\Throwable $e) {
                        }
                    }
                    if (Schema::hasTable('payments')) {
                        try {
                            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
                        } catch (\Throwable $e) {
                        }
                    }
                });
            } catch (\Throwable $e) {
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('tickets')) {
            Schema::dropIfExists('tickets');
        }
    }
};
