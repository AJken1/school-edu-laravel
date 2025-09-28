<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Modify the status column to include more options
            $table->enum('status', [
                'Active', 
                'enrolled', 
                'inactive', 
                'graduated', 
                'pending', 
                'missing_docs', 
                'submitted'
            ])->default('Active')->change();
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Revert to original status options
            $table->enum('status', ['Active', 'enrolled', 'inactive'])->default('Active')->change();
        });
    }
};