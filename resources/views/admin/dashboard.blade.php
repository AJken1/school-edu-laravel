@extends('layouts.admin')

@section('title', 'Admin Dashboard - EDUgate School Management')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center py-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Admin Dashboard</h1>
            <p class="text-muted mb-0">Welcome back, {{ Auth::user()->name }}!</p>
        </div>
        <div class="text-muted">
            <i class="fas fa-calendar-alt me-2"></i>
            {{ now()->format('F j, Y') }}
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <!-- Total Students -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Students
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['studentCount'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Teachers -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Teachers
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['teacherCount'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Subjects -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Subjects
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['subjectCount'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Users
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['totalUsers'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Charts Section -->
    <div class="row">
        <!-- Monthly Enrollment Trend (Line Chart) -->
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Monthly Enrollment Trend {{ $analytics['currentYear'] }}</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="enrollmentChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Status Distribution (Doughnut Chart) -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Student Status Distribution</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Analytics -->
    <div class="row">
        <!-- Grade Level Distribution (Bar Chart) -->
        <div class="col-xl-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Grade Level Distribution</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="gradeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graduation Trends (Line Chart) -->
        <div class="col-xl-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Graduation Trends (Last 5 Years)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="graduationChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Management Sections -->
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Student Management</h6>
                    <a href="{{ route('admin.students.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    <p class="card-text">Manage student records, enrollments, and academic information.</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.students.index') }}" class="btn btn-primary">
                            <i class="fas fa-list me-2"></i>Student List
                        </a>
                        <a href="{{ route('admin.students.create') }}" class="btn btn-outline-primary">
                            <i class="fas fa-plus me-2"></i>Add New Student
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-success">Teacher Management</h6>
                    <a href="{{ route('admin.teachers.index') }}" class="btn btn-sm btn-outline-success">View All</a>
                </div>
                <div class="card-body">
                    <p class="card-text">Manage teacher profiles, assignments, and schedules.</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.teachers.index') }}" class="btn btn-success">
                            <i class="fas fa-list me-2"></i>Teacher List
                        </a>
                        <a href="{{ route('admin.teachers.create') }}" class="btn btn-outline-success">
                            <i class="fas fa-plus me-2"></i>Add New Teacher
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-info">Subject Management</h6>
                    <a href="{{ route('admin.subjects.index') }}" class="btn btn-sm btn-outline-info">View All</a>
                </div>
                <div class="card-body">
                    <p class="card-text">Manage subjects, curriculum, and course information.</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.subjects.index') }}" class="btn btn-info">
                            <i class="fas fa-list me-2"></i>Subject List
                        </a>
                        <a href="{{ route('admin.subjects.create') }}" class="btn btn-outline-info">
                            <i class="fas fa-plus me-2"></i>Add New Subject
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
.chart-area {
    position: relative;
    height: 300px;
}
.chart-pie {
    position: relative;
    height: 250px;
}
.chart-bar {
    position: relative;
    height: 300px;
}
</style>
@endpush

@push('scripts')
<script>
// Chart.js configuration
Chart.defaults.font.family = 'Nunito, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif';
Chart.defaults.color = '#858796';

// Enrollment Chart (Line Chart)
const enrollmentCtx = document.getElementById('enrollmentChart').getContext('2d');
const enrollmentChart = new Chart(enrollmentCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'New Enrollments',
            lineTension: 0.3,
            backgroundColor: 'rgba(78, 115, 223, 0.05)',
            borderColor: 'rgba(78, 115, 223, 1)',
            pointRadius: 3,
            pointBackgroundColor: 'rgba(78, 115, 223, 1)',
            pointBorderColor: 'rgba(78, 115, 223, 1)',
            pointHoverRadius: 3,
            pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
            pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
            pointHitRadius: 10,
            pointBorderWidth: 2,
            data: @json($analytics['monthlyEnrollments'])
        }]
    },
    options: {
        maintainAspectRatio: false,
        layout: {
            padding: {
                left: 10,
                right: 25,
                top: 25,
                bottom: 0
            }
        },
        scales: {
            x: {
                gridLines: {
                    display: false,
                    drawBorder: false
                },
                ticks: {
                    maxTicksLimit: 12
                }
            },
            y: {
                ticks: {
                    maxTicksLimit: 5,
                    padding: 10,
                },
                gridLines: {
                    color: 'rgb(234, 236, 244)',
                    zeroLineColor: 'rgb(234, 236, 244)',
                    drawBorder: false,
                    borderDash: [2],
                    zeroLineBorderDash: [2]
                }
            }
        },
        legend: {
            display: false
        },
        tooltips: {
            backgroundColor: 'rgb(255,255,255)',
            bodyFontColor: '#858796',
            titleMarginBottom: 10,
            titleFontColor: '#6e707e',
            titleFontSize: 14,
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            intersect: false,
            mode: 'index',
            caretPadding: 10,
        }
    }
});

// Status Distribution Chart (Doughnut Chart)
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusChart = new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: @json($analytics['statusDistribution']->pluck('status')),
        datasets: [{
            data: @json($analytics['statusDistribution']->pluck('count')),
            backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'],
            hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#f4b619', '#e02d1b', '#6c757d'],
            hoverBorderColor: 'rgba(234, 236, 244, 1)',
        }]
    },
    options: {
        maintainAspectRatio: false,
        tooltips: {
            backgroundColor: 'rgb(255,255,255)',
            bodyFontColor: '#858796',
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            caretPadding: 10,
        },
        legend: {
            display: false
        },
        cutoutPercentage: 80,
    }
});

// Grade Distribution Chart (Bar Chart)
const gradeCtx = document.getElementById('gradeChart').getContext('2d');
const gradeChart = new Chart(gradeCtx, {
    type: 'bar',
    data: {
        labels: @json($analytics['gradeDistribution']->pluck('grade')),
        datasets: [{
            label: 'Students',
            backgroundColor: '#4e73df',
            hoverBackgroundColor: '#2e59d9',
            borderColor: '#4e73df',
            data: @json($analytics['gradeDistribution']->pluck('count'))
        }]
    },
    options: {
        maintainAspectRatio: false,
        layout: {
            padding: {
                left: 10,
                right: 25,
                top: 25,
                bottom: 0
            }
        },
        scales: {
            x: {
                gridLines: {
                    display: false,
                    drawBorder: false
                },
                maxBarThickness: 25,
            },
            y: {
                ticks: {
                    min: 0,
                    maxTicksLimit: 5,
                    padding: 10,
                },
                gridLines: {
                    color: 'rgb(234, 236, 244)',
                    zeroLineColor: 'rgb(234, 236, 244)',
                    drawBorder: false,
                    borderDash: [2],
                    zeroLineBorderDash: [2]
                }
            }
        },
        legend: {
            display: false
        },
        tooltips: {
            titleMarginBottom: 10,
            titleFontColor: '#6e707e',
            titleFontSize: 14,
            backgroundColor: 'rgb(255,255,255)',
            bodyFontColor: '#858796',
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            caretPadding: 10,
        }
    }
});

// Graduation Trends Chart (Line Chart)
const graduationCtx = document.getElementById('graduationChart').getContext('2d');
const graduationChart = new Chart(graduationCtx, {
    type: 'line',
    data: {
        labels: @json($analytics['graduationData']->pluck('year')),
        datasets: [{
            label: 'Graduated Students',
            lineTension: 0.3,
            backgroundColor: 'rgba(28, 200, 138, 0.05)',
            borderColor: 'rgba(28, 200, 138, 1)',
            pointRadius: 3,
            pointBackgroundColor: 'rgba(28, 200, 138, 1)',
            pointBorderColor: 'rgba(28, 200, 138, 1)',
            pointHoverRadius: 3,
            pointHoverBackgroundColor: 'rgba(28, 200, 138, 1)',
            pointHoverBorderColor: 'rgba(28, 200, 138, 1)',
            pointHitRadius: 10,
            pointBorderWidth: 2,
            data: @json($analytics['graduationData']->pluck('graduated'))
        }]
    },
    options: {
        maintainAspectRatio: false,
        layout: {
            padding: {
                left: 10,
                right: 25,
                top: 25,
                bottom: 0
            }
        },
        scales: {
            x: {
                gridLines: {
                    display: false,
                    drawBorder: false
                },
                ticks: {
                    maxTicksLimit: 5
                }
            },
            y: {
                ticks: {
                    min: 0,
                    maxTicksLimit: 5,
                    padding: 10,
                },
                gridLines: {
                    color: 'rgb(234, 236, 244)',
                    zeroLineColor: 'rgb(234, 236, 244)',
                    drawBorder: false,
                    borderDash: [2],
                    zeroLineBorderDash: [2]
                }
            }
        },
        legend: {
            display: false
        },
        tooltips: {
            backgroundColor: 'rgb(255,255,255)',
            bodyFontColor: '#858796',
            titleMarginBottom: 10,
            titleFontColor: '#6e707e',
            titleFontSize: 14,
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            intersect: false,
            mode: 'index',
            caretPadding: 10,
        }
    }
});

// Auto-refresh dashboard stats every 5 minutes
setInterval(function() {
    fetch('/api/dashboard-stats')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                console.log('Dashboard stats refreshed');
            }
        })
        .catch(error => console.log('Stats refresh error:', error));
}, 300000); // 5 minutes
</script>
@endpush
