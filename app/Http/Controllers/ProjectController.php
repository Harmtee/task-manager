<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the projects.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $projects = Project::all();
        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new project.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created project in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required"
        ]);

        $project = new Project();
        $project->name = $request->name;
        $project->save();

        return redirect()->route('projects.index');
    }

    /**
     * Display the specified project.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function show(Project $project)
    {
        return view('projects.show', compact('project'));
    }

    /**
     * Update the specified project in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function update(Request $request)
    {
        $request->validate([
            "name" => "required",
            "project_id" => "required"
        ]);
        $project = Project::find($request->project_id);
        try {
            $project->name = $request->name;
            $project->save();
            return response(['status' => 'success', 'data' => $project]);
        } catch (\Throwable $th) {
            return response(['status' => 'error', 'message' => 'Error occurred']);
        }
    }

    /**
     * Remove the specified project from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function destroy(Request $request)
    {
        $project = Project::find($request->project_id);
        try {
            $project->delete();
            return response(['status' => 'success']);
        } catch (\Throwable $th) {
            return response(['status' => 'Unable to delete project']);
        }
    }
}
