<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('users')->get();
    return response()->json($projects);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $project = Project::create($request->all());
        return response()->json($project, 201);
    }

    public function show($id)
    {
        return Project::with('users')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $project = Project::findOrFail($id);
        $project->update($request->all());

        return response()->json($project, 200);
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return response()->json(null, 204);
    }

    public function assignDevelopers(Request $request, $id)
    {
        $this->validate($request, [
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $project = Project::findOrFail($id);
        $project->users()->sync($request->user_ids);

        return response()->json($project->load('users'), 200);
    }
}
