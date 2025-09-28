@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="header">
    <div class="left">
        <h1>Dashboard</h1>
        <ul class="breadcrumb">
            <li><a>Analytics</a></li>
        </ul>
    </div>
</div>

<!-- Insights -->
<ul class="insights">
    <li onclick="showTeacherList()">
        <i class='bx bxs-user'></i>
        <span class="info">
            <h3 class="text-center" id="teacherCount">---</h3>
            <p>Teachers</p>
        </span>
    </li>
    <li onclick="showStudentList()">
        <i class='bx bxs-group'></i>
        <span class="info">
            <h3 class="text-center" id="studentCount">---</h3>
            <p>Students</p>
        </span>
    </li>
    <li onclick="showSubjectList()">
        <i class='bx bxs-book'></i>
        <span class="info">
            <h3 class="text-center" id="subjectCount">---</h3>
            <p>Subjects</p>
        </span>
    </li>
</ul>

<!-- Recent Activity -->

@endsection

@push('scripts')
<script>
// Load dashboard statistics
async function loadDashboardStats() {
    try {
        const response = await fetch('/api/dashboard-stats', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        });
        
        const data = await response.json();
        
        if (data.status === 'success') {
            document.getElementById('studentCount').textContent = data.studentCount;
            document.getElementById('teacherCount').textContent = data.teacherCount;
            document.getElementById('subjectCount').textContent = data.subjectCount || 0;
        }
    } catch (error) {
        console.error('Error loading dashboard stats:', error);
    }
}

// Navigation functions
function showTeacherList() {
    window.location.href = "{{ route('admin.teachers.index') }}";
}

function showStudentList() {
    window.location.href = "{{ route('admin.students.index') }}";
}

function showSubjectList() {
    window.location.href = "{{ route('admin.subjects.index') }}";
}

// Load stats on page load
document.addEventListener('DOMContentLoaded', loadDashboardStats);
</script>
@endpush