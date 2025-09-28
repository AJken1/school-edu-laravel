<?php

namespace App\Console\Commands;

use App\Models\Student;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupStudentData extends Command
{
    protected $signature = 'students:cleanup';
    protected $description = 'Clean up NULL values in students table';

    public function handle()
    {
        $this->info('Starting student data cleanup...');

        $students = Student::all();
        $updated = 0;

        foreach ($students as $student) {
            $changes = [];

            // Fix NULL values with default data
            if (is_null($student->school_year)) {
                $changes['school_year'] = date('n') >= 6 
                    ? date('Y') . '-' . (date('Y') + 1)
                    : (date('Y') - 1) . '-' . date('Y');
            }

            if (is_null($student->lrn_number)) {
                $changes['lrn_number'] = 'LRN' . str_pad($student->id, 11, '0', STR_PAD_LEFT);
            }

            if (is_null($student->firstname)) {
                $changes['firstname'] = 'Unknown';
            }

            if (is_null($student->lastname)) {
                $changes['lastname'] = 'Student';
            }

            if (is_null($student->mi)) {
                $changes['mi'] = '';
            }

            if (is_null($student->sex)) {
                $changes['sex'] = 'Male';
            }

            if (is_null($student->date_of_birth)) {
                $changes['date_of_birth'] = '2000-01-01';
            }

            if (is_null($student->religion)) {
                $changes['religion'] = 'Not Specified';
            }

            if (is_null($student->grade)) {
                $changes['grade'] = '1';
            }

            if (is_null($student->current_address)) {
                $changes['current_address'] = 'Not Provided';
            }

            if (is_null($student->contact_number)) {
                $changes['contact_number'] = '0000000000';
            }

            if (!empty($changes)) {
                $student->update($changes);
                $updated++;
                $this->line("Updated student ID: {$student->id}");
            }
        }

        $this->info("Cleanup completed. Updated {$updated} students.");
        
        // Show statistics
        $totalStudents = Student::count();
        $nullCounts = [
            'school_year' => Student::whereNull('school_year')->count(),
            'lrn_number' => Student::whereNull('lrn_number')->count(),
            'firstname' => Student::whereNull('firstname')->count(),
            'lastname' => Student::whereNull('lastname')->count(),
            'sex' => Student::whereNull('sex')->count(),
            'date_of_birth' => Student::whereNull('date_of_birth')->count(),
            'religion' => Student::whereNull('religion')->count(),
            'grade' => Student::whereNull('grade')->count(),
            'current_address' => Student::whereNull('current_address')->count(),
            'contact_number' => Student::whereNull('contact_number')->count(),
        ];

        $this->table(
            ['Field', 'NULL Count', 'Percentage'],
            collect($nullCounts)->map(function ($count, $field) use ($totalStudents) {
                return [
                    $field,
                    $count,
                    $totalStudents > 0 ? round(($count / $totalStudents) * 100, 2) . '%' : '0%'
                ];
            })
        );
    }
}
