<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $projects = Project::all();
        $tasks = Task::orderBy('priority');
        if ($request->project) {
            $tasks = $tasks->where('project_id', $request->project);
        }
        $tasks = $tasks->get();

        return view('tasks.index', compact('tasks', 'projects'));
    }


    /**
     * Store a newly created task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $task = new Task();
        $task->name = $request->name;
        $task->project_id = $request->project_id;
        $task->priority = Task::count() + 1;
        $task->save();

        return redirect()->route('tasks.index');
    }

    /**
     * Show the form for editing the specified task.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\View\View
     */
    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return string?int
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            "name"=> "required"
        ]);
        try {
            $task->name = $request->name;
            $task->project_id = $request->project_id;
            $task->save();
            return response($task);
        } catch (\Throwable $th) {
            return 0;
        }
    }

    /**
     * Remove the specified task from storage.
     *
     * @param  \App\Models\Task  $task
     * @return int
     */
    public function destroy(Task $task)
    {
        try {
            $task->delete();
            return 1;
        } catch (\Throwable $th) {
            return 0;
        }
    }

    /**
     * Update the priority of a task and adjust other affected tasks' priorities.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    public function updatePriority(Request $request)
    {
        $taskIds = $request->taskIds;

        // Loop through the received task IDs and update their priorities
        foreach ($taskIds as $index => $taskId) {
            $task = Task::find($taskId);
            if ($task) {
                $task->priority = $index + 1; // Update the priority based on the index
                $task->save();
            }
        }

        return response()->json(['message' => 'Task priorities updated successfully']);
    }
}
