<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Task - {{ $task->task_name }}</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/show.css') }}">
    
</head>
<body>
    <div class="container dashboard-container py-5">
        <!-- Top Navigation / Back -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-white fs-3 fw-bold mb-0">Task Details</h1>
            <a href="/tasks" class="btn btn-custom-back shadow-sm">← Back to Tasks</a>
        </div>

        <!-- Main Details Card -->
        <div class="content-panel p-4 p-md-5">
            <div class="row g-4">
                <!-- Task Name -->
                <div class="col-12">
                    <div class="detail-label">Task Name</div>
                    <div class="detail-value fs-4 fw-bold text-white">{{ $task->task_name }}</div>
                </div>

                <div class="col-12"><hr class="text-muted opacity-25 my-0"></div>

                <!-- Status -->
                <div class="col-md-4">
                    <div class="detail-label">Status</div>
                    <div>
                        <span class="badge badge-custom {{ $task->status == 'Completed' ? 'badge-completed' : 'badge-pending' }}">
                            {{ $task->status }}
                        </span>
                    </div>
                </div>

                <!-- Created Date -->
                <div class="col-md-4">
                    <div class="detail-label">Created At</div>
                    <div class="detail-value text-white-50">{{ $task->created_at->format('M d, Y h:i A') }}</div>
                </div>

                <!-- Due Date -->
                <div class="col-md-4">
                    <div class="detail-label">Due Date</div>
                    <div class="detail-value text-white-50">{{ $task->due_date ?? 'No due date set' }}</div>
                </div>

                <div class="col-12"><hr class="text-muted opacity-25 my-0"></div>

                <!-- Description -->
                <div class="col-12">
                    <div class="detail-label">Description</div>
                    <div class="detail-value text-white-50 p-3 rounded-3" style="background-color: rgba(15, 23, 42, 0.5); border: 1px solid var(--border-color); white-space: pre-line; min-height: 100px;">
                        {{ $task->description ?: 'No description provided for this task.' }}
                    </div>
                </div>
            </div>

            <!-- Action Buttons Footer -->
            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top" style="border-color: var(--border-color) !important;">
                <a href="/tasks/{{ $task->id }}/edit" class="btn btn-light px-4 text-warning">Edit</a>
                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-light px-4 text-danger" onclick="return confirm('Are you sure you want to delete this task?')">Delete</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>