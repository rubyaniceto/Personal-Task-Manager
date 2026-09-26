<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Task - Personal Task Manager</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom Professional Dark Theme & Gradient Styles -->
    <link rel="stylesheet" href="{{ asset('css/create.css') }}">
</head>
<body>
    <div class="container dashboard-container py-5">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-white fs-3 fw-bold mb-0">Add New Task</h1>
            <a href="javascript:history.back()" class="btn btn-light shadow-sm">← Back</a>
        </div>

        <!-- Form Card Panel -->
        <div class="content-panel p-4 p-md-5">
            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Task Name</label>
                    <input type="text" name="task_name" class="form-control" placeholder="Enter task name..." required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="4" class="form-control" placeholder="Optional details about this task..."></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label">Due Date</label>
                    <input type="date" name="due_date" class="form-control">
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top" style="border-color: var(--border-color) !important;">
                    <a href="javascript:history.back()" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary-custom shadow-sm">Save Task</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>