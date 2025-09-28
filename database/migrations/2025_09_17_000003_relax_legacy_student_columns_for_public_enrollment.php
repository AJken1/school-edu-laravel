<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('students')) {
            return;
        }

        // Drop FK on user_id if present and make it nullable
        try {
            DB::statement('ALTER TABLE `students` DROP FOREIGN KEY `students_user_id_foreign`');
        } catch (\Throwable $e) {
            // ignore if FK not present
        }

        // Make legacy required columns nullable to allow public application records
        $statements = [
            // ids and associations
            "ALTER TABLE `students` MODIFY COLUMN `user_id` BIGINT UNSIGNED NULL",

            // legacy identity fields (admin enrollment path)
            "ALTER TABLE `students` MODIFY COLUMN `school_year` VARCHAR(255) NULL",
            "ALTER TABLE `students` MODIFY COLUMN `lrn_number` VARCHAR(255) NULL",
            "ALTER TABLE `students` MODIFY COLUMN `firstname` VARCHAR(255) NULL",
            "ALTER TABLE `students` MODIFY COLUMN `lastname` VARCHAR(255) NULL",
            "ALTER TABLE `students` MODIFY COLUMN `mi` VARCHAR(10) NULL",
            "ALTER TABLE `students` MODIFY COLUMN `sex` ENUM('Male','Female') NULL",
            "ALTER TABLE `students` MODIFY COLUMN `date_of_birth` DATE NULL",
            "ALTER TABLE `students` MODIFY COLUMN `religion` VARCHAR(100) NULL",
            "ALTER TABLE `students` MODIFY COLUMN `grade` VARCHAR(20) NULL",
            "ALTER TABLE `students` MODIFY COLUMN `current_address` VARCHAR(200) NULL",
            "ALTER TABLE `students` MODIFY COLUMN `contact_number` VARCHAR(20) NULL",
        ];

        foreach ($statements as $sql) {
            try { DB::statement($sql); } catch (\Throwable $e) { /* ignore if column missing */ }
        }
    }

    public function down(): void
    {
        // Intentionally left non-destructive; reverting NOT NULLs may fail on existing data
    }
};


