<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trash Bin - Personal Task Manager</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
     <link rel="stylesheet" href="{{ asset('css/trash.css') }}">
    
</head>
<body>
    <div class="container dashboard-container py-5">
        <!-- App Header -->
        <div class="app-header d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="text-white fs-3 fw-bold mb-1">Trash Bin</h1>
                <p class="text-secondary fs-6 mb-0">Manage deleted tasks. Restore or permanently delete them.</p>
            </div>
            <a href="/tasks" class="btn btn-custom-back shadow-sm">← Back to Tasks</a>
        </div>

        <!-- Success Alerts -->
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
        @endif

        <!-- Trashed Tasks Table Panel -->
        <div class="content-panel">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Name</th>
                            <th>Description</th>
                            <th>Deleted At</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($trashedTasks as $task)
                        <tr>
                            <td class="ps-4 fw-semibold text-white">{{ $task->task_name }}</td>
                            <td class="text-white-50 text-truncate" style="max-width: 250px;">{{ $task->description }}</td>
                            <td class="text-white-50 small">{{ $task->deleted_at->format('M d, Y h:i A') }}</td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end align-items-center gap-2">
                                    <!-- Restore Form -->
                                    <form action="{{ route('tasks.restore', $task->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-light px-2 py-1 text-success">Restore</button>
                                    </form>

                                    <!-- Permanent Delete Form with Modal Trigger -->
                                    <form action="{{ route('tasks.force-delete', $task->id) }}" method="POST" class="d-inline force-delete-form-{{ $task->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                class="btn btn-sm btn-light px-2 py-1 text-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal" 
                                                data-form-class="force-delete-form-{{ $task->id }}">
                                            Delete Forever
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <div class="py-3">
                                    <p class="mb-0 text-white-50">Your trash bin is empty.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content custom-modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title text-white fw-bold" id="deleteModalLabel">Confirm Permanent Deletion</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#ef4444" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                        </svg>
                    </div>
                    <p class="text-white fs-5 mb-1">Are you sure you want to permanently delete this task?</p>
                    <p class="text-muted small mb-0">This action cannot be undone.</p>
                </div>
                <div class="modal-footer border-0 pt-0 justify-content-center gap-2">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger px-4" id="confirmDeleteBtn">Yes, Delete Forever</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Delete Modal Script -->
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