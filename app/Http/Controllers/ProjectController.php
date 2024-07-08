<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('client', 'estimations')->get()->map(function ($project) {
            $project->estimate = $project->estimate_sum;
            return $project;
        });
        
        return response()->json($projects);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'client_id' => 'required|exists:clients,id',
        ]);

        $project = Project::create($request->all());
        return response()->json($project, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'client_id' => 'required|exists:clients,id',
        ]);

        $project = Project::findOrFail($id);
        $project->update($request->all());
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();
    }
}