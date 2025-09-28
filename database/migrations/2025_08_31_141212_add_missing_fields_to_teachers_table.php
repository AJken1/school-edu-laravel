<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            // Add missing fields that the controller expects
            $table->string('firstname')->nullable()->after('name');
            $table->string('lastname')->nullable()->after('firstname');
            $table->string('employee_id')->nullable()->unique()->after('lastname');
            $table->string('email')->nullable()->after('employee_id');
            $table->string('department')->nullable()->after('position');
            $table->string('contact_number', 20)->nullable()->after('phone');
            $table->string('profile_picture')->nullable()->after('image');
            $table->enum('status', ['Active', 'inactive'])->default('Active')->after('profile_picture');
        });
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn([
                'firstname', 
                'lastname', 
                'employee_id', 
                'email', 
                'department', 
                'contact_number', 
                'profile_picture', 
                'status'
            ]);
        });
    }
};