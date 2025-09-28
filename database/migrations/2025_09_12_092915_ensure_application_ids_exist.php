<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ensure students.application_id exists
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'application_id')) {
                $table->string('application_id')->unique()->nullable()->after('id');
            }
        });

        // Backfill missing application_id for existing student rows
        $students = DB::table('students')->whereNull('application_id')->get(['id']);
        foreach ($students as $row) {
            DB::table('students')
                ->where('id', $row->id)
                ->update([
                    'application_id' => 'S-' . date('Y') . '-' . str_pad((int) (microtime(true) * 1000) % 1000000, 6, '0', STR_PAD_LEFT),
                ]);
            // Sleep a tiny amount to reduce collision probability
            usleep(5000);
        }

        // Ensure teachers.application_id exists
        Schema::table('teachers', function (Blueprint $table) {
            if (!Schema::hasColumn('teachers', 'application_id')) {
                $table->string('application_id')->unique()->nullable()->after('id');
            }
        });

        // Backfill missing application_id for existing teacher rows
        $teachers = DB::table('teachers')->whereNull('application_id')->get(['id']);
        foreach ($teachers as $row) {
            DB::table('teachers')
                ->where('id', $row->id)
                ->update([
                    'application_id' => 'T-' . date('Y') . '-' . str_pad((int) (microtime(true) * 1000) % 1000000, 6, '0', STR_PAD_LEFT),
                ]);
            usleep(5000);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Do not drop columns on down to avoid data loss; safe no-op
    }
};
