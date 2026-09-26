<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
   public function index(Request $request)
    {
        $search = $request->input('search');
        $statusFilter = $request->input('status');

        $query = Task::query();

        // Search by task name or description
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('task_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($statusFilter && $statusFilter != 'All') {
            $query->where('status', $statusFilter);
        }

        $tasks = $query->get();

        // Dashboard overall stats (keeps cards accurate regardless of filter)
        $allTasks = Task::all();
        $totalTasks = $allTasks->count();
        $completedTasks = $allTasks->where('status', 'Completed')->count();
        $pendingTasks = $allTasks->where('status', 'Pending')->count();

        return view('tasks.index', compact('tasks', 'totalTasks', 'completedTasks', 'pendingTasks', 'search', 'statusFilter'));
    }
    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'task_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        Task::create([
            'task_name' => $request->task_name,
            'description' => $request->description,
            'status' => 'Pending',
            'due_date' => $request->due_date,
        ]);

        return redirect('/tasks')->with('success', 'Task created successfully.');
    }

    public function show(string $id)
    {
        $task = Task::findOrFail($id);
        return view('tasks.show', compact('task'));
    }

    public function edit(string $id)
    {
        $task = Task::findOrFail($id);
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'task_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:Pending,Completed',
            'due_date' => 'nullable|date',
        ]);

        $task = Task::findOrFail($id);
        $task->update($request->all());

        return redirect('/tasks')->with('success', 'Task updated successfully.');
    }

    public function destroy(string $id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return redirect('/tasks')->with('success', 'Task deleted successfully.');
    }
    public function toggleStatus(Task $task)
    {
        // Switch status between Completed and Pending
        $task->status = $task->status === 'Completed' ? 'Pending' : 'Completed';
        $task->save();

        return redirect('/tasks')->with('success', 'Task status updated!');
    }

    // View soft-deleted tasks
public function trash()
{
    $trashedTasks = Task::onlyTrashed()->orderBy('deleted_at', 'desc')->get();
    return view('tasks.trash', compact('trashedTasks'));
}

// Restore a deleted task
public function restore($id)
{
    $task = Task::onlyTrashed()->findOrFail($id);
    $task->restore();

    return redirect()->route('tasks.trash')->with('success', 'Task restored successfully.');
}

// Permanently delete a task
public function forceDelete($id)
{
    $task = Task::onlyTrashed()->findOrFail($id);
    $task->forceDelete();

    return redirect()->route('tasks.trash')->with('success', 'Task permanently deleted.');
}
}