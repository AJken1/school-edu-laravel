<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First, populate missing data from duplicate fields
        DB::statement("
            UPDATE students 
            SET firstname = COALESCE(firstname, first_name),
                lastname = COALESCE(lastname, last_name),
                phone = COALESCE(phone, contact_number)
            WHERE firstname IS NULL OR lastname IS NULL
        ");

        // Drop duplicate columns
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name']);
        });

        // Make sure required fields are not null for existing records
        DB::statement("
            UPDATE students 
            SET school_year = COALESCE(school_year, '2025-2026'),
                lrn_number = COALESCE(lrn_number, CONCAT('LRN', id)),
                firstname = COALESCE(firstname, 'Unknown'),
                lastname = COALESCE(lastname, 'Student'),
                sex = COALESCE(sex, 'Male'),
                date_of_birth = COALESCE(date_of_birth, '2000-01-01'),
                religion = COALESCE(religion, 'Not Specified'),
                grade = COALESCE(grade, '1'),
                current_address = COALESCE(current_address, 'Not Provided'),
                contact_number = COALESCE(contact_number, '0000000000')
            WHERE school_year IS NULL 
               OR lrn_number IS NULL 
               OR firstname IS NULL 
               OR lastname IS NULL 
               OR sex IS NULL 
               OR date_of_birth IS NULL 
               OR religion IS NULL 
               OR grade IS NULL 
               OR current_address IS NULL 
               OR contact_number IS NULL
        ");

        // Make required fields NOT NULL again
        Schema::table('students', function (Blueprint $table) {
            $table->string('school_year')->nullable(false)->change();
            $table->string('lrn_number')->nullable(false)->change();
            $table->string('firstname')->nullable(false)->change();
            $table->string('lastname')->nullable(false)->change();
            $table->string('mi', 10)->nullable(false)->change();
            $table->enum('sex', ['Male', 'Female'])->nullable(false)->change();
            $table->date('date_of_birth')->nullable(false)->change();
            $table->string('religion', 100)->nullable(false)->change();
            $table->string('grade', 20)->nullable(false)->change();
            $table->string('current_address', 200)->nullable(false)->change();
            $table->string('contact_number', 20)->nullable(false)->change();
        });
    }

    public function down(): void
    {
        // Add back the duplicate columns if needed
        Schema::table('students', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('firstname');
            $table->string('last_name')->nullable()->after('first_name');
        });

        // Copy data back
        DB::statement("
            UPDATE students 
            SET first_name = firstname,
                last_name = lastname
        ");
    }
};
