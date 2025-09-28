<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'application_id')) {
                $table->string('application_id')->nullable()->unique()->after('id');
            }
            if (!Schema::hasColumn('students', 'first_name')) {
                $table->string('first_name')->nullable()->after('application_id');
            }
            if (!Schema::hasColumn('students', 'last_name')) {
                $table->string('last_name')->nullable()->after('first_name');
            }
            if (!Schema::hasColumn('students', 'email')) {
                $table->string('email')->nullable()->unique()->after('last_name');
            }
            if (!Schema::hasColumn('students', 'phone')) {
                $table->string('phone', 20)->nullable()->after('email');
            }
            if (!Schema::hasColumn('students', 'gender')) {
                $table->string('gender')->nullable()->after('sex');
            }
            if (!Schema::hasColumn('students', 'address')) {
                $table->string('address', 500)->nullable()->after('current_address');
            }
            if (!Schema::hasColumn('students', 'grade_level')) {
                $table->integer('grade_level')->nullable()->after('grade');
            }
            if (!Schema::hasColumn('students', 'previous_school')) {
                $table->string('previous_school')->nullable()->after('grade_level');
            }
            if (!Schema::hasColumn('students', 'parent_name')) {
                $table->string('parent_name')->nullable()->after('previous_school');
            }
            if (!Schema::hasColumn('students', 'parent_phone')) {
                $table->string('parent_phone', 20)->nullable()->after('parent_name');
            }
            if (!Schema::hasColumn('students', 'parent_email')) {
                $table->string('parent_email')->nullable()->after('parent_phone');
            }
            if (!Schema::hasColumn('students', 'relationship')) {
                $table->string('relationship', 50)->nullable()->after('parent_email');
            }
            if (!Schema::hasColumn('students', 'medical_conditions')) {
                $table->text('medical_conditions')->nullable()->after('pwd_details');
            }
            if (!Schema::hasColumn('students', 'additional_notes')) {
                $table->text('additional_notes')->nullable()->after('medical_conditions');
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $dropColumns = [
                'application_id', 'first_name', 'last_name', 'email', 'phone',
                'gender', 'address', 'grade_level', 'previous_school',
                'parent_name', 'parent_phone', 'parent_email', 'relationship',
                'medical_conditions', 'additional_notes',
            ];

            foreach ($dropColumns as $column) {
                if (Schema::hasColumn('students', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};


