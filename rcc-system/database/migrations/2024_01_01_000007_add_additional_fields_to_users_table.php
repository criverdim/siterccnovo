<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->after('email');
            $table->string('whatsapp')->after('phone');
            $table->date('birth_date')->nullable()->after('whatsapp');
            $table->string('cpf')->nullable()->after('birth_date');
            $table->string('cep')->nullable()->after('cpf');
            $table->string('address')->nullable()->after('cep');
            $table->string('number')->nullable()->after('address');
            $table->string('complement')->nullable()->after('number');
            $table->string('district')->nullable()->after('complement');
            $table->string('city')->nullable()->after('district');
            $table->string('state')->nullable()->after('city');
            $table->string('gender')->nullable()->after('state');
            $table->foreignId('group_id')->nullable()->constrained()->onDelete('set null')->after('gender');
            $table->boolean('is_servo')->default(false)->after('group_id');
            $table->timestamp('profile_completed_at')->nullable()->after('is_servo');
            $table->timestamp('consent_at')->nullable()->after('profile_completed_at');
            $table->string('status')->default('active')->after('consent_at'); // active, inactive, blocked
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
            $table->dropColumn([
                'phone', 'whatsapp', 'birth_date', 'cpf', 'cep', 'address',
                'number', 'complement', 'district', 'city', 'state', 'gender',
                'group_id', 'is_servo', 'profile_completed_at', 'consent_at', 'status',
            ]);
        });
    }
};
