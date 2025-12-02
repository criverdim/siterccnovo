<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->string('responsible_phone')->nullable()->after('responsible');
            $table->string('responsible_whatsapp')->nullable()->after('responsible_phone');
            $table->string('responsible_email')->nullable()->after('responsible_whatsapp');
        });
    }

    public function down(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn(['responsible_phone', 'responsible_whatsapp', 'responsible_email']);
        });
    }
};
