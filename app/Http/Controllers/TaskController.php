<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request, ?Project $project = null)
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
        $request->validate(['name' => 'required']);
        $task = new Task();
        $task->name = $request->name;
        $task->project_id = $request->project_id;
        $task->priority = Task::count() + 1;
        $task->save();

        return redirect()->route('tasks.index');
    }

    /**
     * Update the specified task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return string?int
     */
    public function update(Request $request)
    {
        $request->validate([
            "name" => "required",
            "task_id" => "required",
            "project_id" => "nullable",
        ]);
        $task = Task::find($request->task_id);
        try {
            $task->name = $request->name;
            $task->project_id = $request->project_id;
            $task->save();
            return response(['status' => 'success', 'data' => $task]);
        } catch (\Throwable $th) {
            return response(['status' => 'error']);
        }
    }

    /**
     * Remove the specified task from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function destroy(Task $task)
    {
        try {
            $task->delete();
            return response(['status' => 'success', 'message' => 'Deleted successfully']);
        } catch (\Throwable $th) {
            return response(['status' => 'error', 'message' => 'Unable to delete']);
        }
    }

    /**
     * Update the priority of a task and adjust other affected tasks' priorities.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function updatePriority(Request $request)
    {
        $request->validate([
            'task' => 'required',
            'destination' => 'required',
        ]);
        $source = Task::find($request->task);
        $destination = Task::find($request->destination);
        try {
            if ($source && $destination) {
                $sp = $source->priority;
                $dp = $destination->priority;

                //Get all tasks between priority
                $tasks = (Task::whereBetween('priority', [min($source->priority, $destination->priority), max($source->priority, $destination->priority)])
                    ->where('id', '!=', $source->id)
                    ->orderBy('priority', $sp < $dp ? 'asc' : 'desc'))->get();
                DB::beginTransaction();
                $source->update(['priority' => $dp]);
                foreach ($tasks as $task) {
                    $temp = $task->priority;
                    $task->update(['priority' => $sp]);
                    $sp = $temp;
                }
                DB::commit();
                return response()->json(['status' => 'success', 'message' => 'Task priorities updated successfully']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Cannot find record']);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Server error']);
        }
    }
}
