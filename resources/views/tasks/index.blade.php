<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Task Manager</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

   <link rel="stylesheet" href="{{ asset('css/index.css') }}">

</head>
<body>
    <div class="container dashboard-container py-5">
        <!-- App Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h2 fw-bold mb-1 text-white">Back<span style="color: #be3b1a;">Tasks</span> Manager</h1>
                <p class="text-white-50 small mb-0">Manage and track your daily tasks seamlessly.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('tasks.trash') }}" class="btn btn-light d-inline-flex align-items-center gap-2 shadow-sm">
                    Trash Bin
                </a>
                <a href="/tasks/create" class="btn btn-primary-custom d-inline-flex align-items-center gap-2 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z"/>
                    </svg>
                    New Task
                </a>
            </div>
        </div>

        <!-- Dashboard Stat Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="stat-card p-4">
                    <div class="stat-label mb-1">Total Tasks</div>
                    <div class="stat-value">{{ $totalTasks }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card p-4">
                    <div class="stat-label mb-1">Pending Tasks</div>
                    <div class="stat-value" style="color: #fbbf24;">{{ $pendingTasks }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card p-4">
                    <div class="stat-label mb-1">Completed Tasks</div>
                    <div class="stat-value" style="color: #34d399;">{{ $completedTasks }}</div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="content-panel">
            <!-- Filter Toolbar -->
            <div class="p-4 border-bottom" style="border-color: var(--border-color) !important;">
                <form method="GET" action="/tasks" class="row g-3 align-items-center" id="filterForm">
                    <div class="col-md-7">
                        <div class="position-relative">
                            <input type="text" name="search" id="searchInput" class="form-control" placeholder="Search tasks by name or description..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="status" id="statusSelect" class="form-select">
                            <option value="All" {{ request('status') == 'All' ? 'selected' : '' }}>All Statuses</option>
                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <a href="/tasks" class="btn btn-light w-100 py-2">Reset</a>
                    </div>
                </form>
            </div>

            <!-- Success Alerts -->
           
            <!-- Tasks Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Name</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Due Date</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $task)
                        <tr>
                            <td class="ps-4 fw-semibold text-white">{{ $task->task_name }}</td>
                            <td class="text-muted text-truncate" style="max-width: 200px;">{{ $task->description }}</td>
                            <td>
                                <span class="badge badge-custom {{ $task->status == 'Completed' ? 'badge-completed' : 'badge-pending' }}">
                                    {{ $task->status }}
                                </span>
                            </td>
                            <td class="text-muted small">{{ $task->created_at->format('M d, Y') }}</td>
                            <td class="text-muted">{{ $task->due_date }}</td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end align-items-center gap-2">
                                    <a href="/tasks/{{ $task->id }}" class="btn btn-sm btn-light px-2 py-1 text-info">View</a>
                                    <a href="/tasks/{{ $task->id }}/edit" class="btn btn-sm btn-light px-2 py-1 text-warning">Edit</a>
                                    <!-- Inside your <table><tbody> -->
                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="d-inline delete-form-{{ $task->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                class="btn btn-sm btn-light px-2 py-1 text-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal" 
                                                data-form-class="delete-form-{{ $task->id }}">
                                            Delete
                                        </button>
                                    </form>
                                    <form action="{{ route('tasks.toggle', $task->id) }}" method="POST" class="d-inline ms-1">
                                        @csrf
                                        @method('PATCH')
                                        <input type="checkbox"
                                            class="form-check-input bg-dark border-secondary"
                                            style="transform: scale(1.25); cursor: pointer;"
                                            onchange="this.form.submit()"
                                            {{ $task->status == 'Completed' ? 'checked' : '' }}
                                            title="Toggle completion status">
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <div class="py-3">
                                    <p class="mb-0">No tasks found matching your criteria.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Success Popup Modal -->
        @if(session('success'))
        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content custom-modal-content">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title text-white fw-bold" id="successModalLabel">Success!</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <!-- Success Check Icon -->
                        <div class="mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#34d399" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                            </svg>
                        </div>
                        <p class="text-white fs-5 mb-0">{{ session('success') }}</p>
                    </div>
                    <div class="modal-footer border-0 pt-0 justify-content-center">
                        <button type="button" class="btn btn-success px-4 text-white" style="background-color: #34d399; border: none;" data-bs-dismiss="modal">Got it</button>
                    </div>
                </div>
            </div>
        </div>
       
        @if(session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                        successModal.show();
                    });
                </script>
        @endif
@endif

    <!-- Auto-submit script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('filterForm');
            const searchInput = document.getElementById('searchInput');
            const statusSelect = document.getElementById('statusSelect');
            let debounceTimer;
            statusSelect.addEventListener('change', function() {
                form.submit();
            });
            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function() {
                    form.submit();
                }, 500);
            });
        });
    </script>
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content custom-modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title text-white fw-bold" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#ef4444" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                        </svg>
                    </div>
                    <p class="text-white fs-5 mb-1">Are you sure you want to delete this task?</p>
                    <p class="text-muted small mb-0">This action cannot be undone.</p>
                </div>
                <div class="modal-footer border-0 pt-0 justify-content-center gap-2">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger px-4" id="confirmDeleteBtn">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let formToSubmit = null;
            const deleteModal = document.getElementById('deleteModal');

            deleteModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const formClass = button.getAttribute('data-form-class');
                formToSubmit = document.querySelector('.' + formClass);
            });

            document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                if (formToSubmit) {
                    formToSubmit.submit();
                }
            });
        });
    </script>
</body>
</html>