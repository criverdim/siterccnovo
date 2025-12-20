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
                if (! Schema::hasColumn('events', 'status')) {
                    $table->string('status')->default('active');
                }
                if (! Schema::hasColumn('events', 'tickets_sold')) {
                    $table->integer('tickets_sold')->default(0);
                }
                if (! Schema::hasColumn('events', 'is_active')) {
                    $table->boolean('is_active')->default(true);
                }
            });

            // Add indexes separately to avoid failures when columns already exist
            try {
                Schema::table('events', function (Blueprint $table) {
                    if (Schema::hasColumn('events', 'status')) {
                        $table->index('status');
                    }
                    if (Schema::hasColumn('events', 'start_date')) {
                        $table->index('start_date');
                    }
                });
            } catch (\Throwable $e) {
                // ignore
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                if (Schema::hasColumn('events', 'status')) {
                    $table->dropIndex(['status']);
                    $table->dropColumn('status');
                }
                // keep tickets_sold and is_active for backward compatibility
            });
        }
    }
};
