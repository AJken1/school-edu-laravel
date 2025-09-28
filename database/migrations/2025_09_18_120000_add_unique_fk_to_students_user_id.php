<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('students')) {
            return;
        }

        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            } else {
                // Ensure nullable
                try {
                    $table->unsignedBigInteger('user_id')->nullable()->change();
                } catch (\Throwable $e) {
                    // ignore if platform does not support change()
                }

                // Drop existing FK if present to re-add with nullOnDelete
                try { 
                    DB::statement('ALTER TABLE students DROP FOREIGN KEY students_user_id_foreign');
                } catch (\Throwable $e) { 
                    try { 
                        DB::statement('ALTER TABLE students DROP FOREIGN KEY students_user_id_foreign'); 
                    } catch (\Throwable $e2) { 
                        /* ignore */ 
                    } 
                }

                // Re-add FK with ON DELETE SET NULL
                try { $table->foreign('user_id')->references('id')->on('users')->nullOnDelete(); } catch (\Throwable $e) { /* ignore */ }
            }

            // Unique one-to-one constraint (allow multiple NULLs)
            try { $table->unique('user_id'); } catch (\Throwable $e) { /* ignore if already unique */ }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('students')) {
            return;
        }

        Schema::table('students', function (Blueprint $table) {
            try { $table->dropUnique(['user_id']); } catch (\Throwable $e) { /* ignore */ }
            try { $table->dropForeign(['user_id']); } catch (\Throwable $e) { /* ignore */ }
            try { $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete(); } catch (\Throwable $e) { /* ignore */ }
        });
    }
};


