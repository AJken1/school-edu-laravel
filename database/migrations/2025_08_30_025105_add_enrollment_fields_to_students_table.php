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
        Schema::table('students', function (Blueprint $table) {
            $table->string('application_id')->unique()->nullable()->after('id');
            $table->string('first_name')->nullable()->after('application_id');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('email')->unique()->nullable()->after('last_name');
            $table->string('phone', 20)->nullable()->after('email');
            $table->string('gender')->nullable()->after('sex');
            $table->string('address', 500)->nullable()->after('current_address');
            $table->integer('grade_level')->nullable()->after('grade');
            $table->string('previous_school')->nullable()->after('grade_level');
            $table->string('parent_name')->nullable()->after('previous_school');
            $table->string('parent_phone', 20)->nullable()->after('parent_name');
            $table->string('parent_email')->nullable()->after('parent_phone');
            $table->string('relationship', 50)->nullable()->after('parent_email');
            $table->text('medical_conditions')->nullable()->after('pwd_details');
            $table->text('additional_notes')->nullable()->after('medical_conditions');
            $table->string('status', 50)->change()->default('submitted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'application_id', 'first_name', 'last_name', 'email', 'phone', 
                'gender', 'address', 'grade_level', 'previous_school', 
                'parent_name', 'parent_phone', 'parent_email', 'relationship',
                'medical_conditions', 'additional_notes'
            ]);
        });
    }
};
