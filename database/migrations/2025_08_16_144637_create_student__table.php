<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('school_year');
            $table->string('lrn_number')->unique();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('mi', 10);
            $table->enum('sex', ['Male', 'Female']);
            $table->date('date_of_birth');
            $table->string('religion', 100);
            $table->string('grade', 20);
            $table->string('current_address', 200);
            $table->boolean('pwd')->default(false);
            $table->string('pwd_details', 200)->nullable();
            $table->string('father_firstname', 100)->nullable();
            $table->string('father_lastname', 100)->nullable();
            $table->string('father_mi', 10)->nullable();
            $table->string('mother_firstname', 100)->nullable();
            $table->string('mother_lastname', 100)->nullable();
            $table->string('mother_mi', 10)->nullable();
            $table->string('guardian_firstname', 100)->nullable();
            $table->string('guardian_lastname', 100)->nullable();
            $table->string('guardian_mi', 10)->nullable();
            $table->string('contact_number', 20);
            $table->enum('status', ['Active', 'enrolled', 'inactive'])->default('Active');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};