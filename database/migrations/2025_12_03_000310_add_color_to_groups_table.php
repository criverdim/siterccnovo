<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            if (!Schema::hasColumn('groups', 'color_hex')) {
                $table->string('color_hex', 9)->nullable()->after('weekday');
            }
        });
    }

    public function down(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            if (Schema::hasColumn('groups', 'color_hex')) {
                $table->dropColumn('color_hex');
            }
        });
    }
};

