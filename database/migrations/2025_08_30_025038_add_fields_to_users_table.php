<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_id')->nullable()->after('id');
            $table->string('employee_id')->nullable()->unique()->after('user_id');
            $table->string('phone')->nullable()->after('email');
            $table->string('department')->nullable()->after('phone');
            $table->enum('role', ['admin', 'teacher', 'student', 'owner', 'staff'])->default('student')->after('department');
            $table->enum('status', ['active', 'inactive', 'pending'])->default('active')->after('role');
            $table->enum('theme', ['light', 'dark'])->default('light')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['user_id', 'employee_id', 'phone', 'department', 'role', 'status', 'theme']);
        });
    }
};
