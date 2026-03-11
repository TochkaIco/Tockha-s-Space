<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\CreateTask;
use App\Actions\UpdateTask;
use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tasks = Auth::user()
            ->tasks()
            ->when($request->status, fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->get();

        return view('tasks.index', [
            'tasks' => $tasks,
            'statusCounts' => Task::statusCounts(Auth::user()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): void
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request, CreateTask $action)
    {
        $action->handle($request->safe()->all());

        return to_route('tasks.index')
            ->with('success', 'Task created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return view('tasks.show', [
            'task' => $task,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, Task $task, UpdateTask $action)
    {
        $action->handle($request->safe()->all(), $task);

        return back()->with('success', 'Task updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return to_route('tasks.index');
    }
}
