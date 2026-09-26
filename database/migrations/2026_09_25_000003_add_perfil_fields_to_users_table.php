<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('apellido_paterno')->nullable()->after('nombres');
            $table->string('apellido_materno')->nullable()->after('apellido_paterno');
            $table->string('foto')->nullable()->after('password');
            $table->foreignId('roles_id')->nullable()->after('foto')->constrained('roles')->nullOnDelete();
            $table->foreignId('estatus_id')->nullable()->after('roles_id')->constrained('estatus')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('estatus_id');
            $table->dropConstrainedForeignId('roles_id');
            $table->dropColumn(['apellido_paterno', 'apellido_materno', 'foto']);
        });
    }
};
