<?php

namespace App\Http\Controllers;

use App\Models\Estimation;
use Illuminate\Http\Request;

class EstimationController extends Controller
{
    public function index()
    {
        return Estimation::with('project', 'client')->get();
    }
    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'client_id' => 'required|exists:clients,id',
            'amount' => 'required|numeric|min:0',
        ]);

        $estimation = Estimation::create($request->all());
        $project = $estimation->project;
        $project->estimate_sum = $project->estimations->sum('amount');
        $project->save();
        
        return response()->json($estimation, 201);
    }    

    public function update(Request $request, $id)
    {
        $request->validate([
            'project_id' => 'sometimes|required|exists:projects,id',
            'client_id' => 'sometimes|required|exists:clients,id',
            'amount' => 'sometimes|required|numeric|min:0',
        ]);

        $estimation = Estimation::findOrFail($id);
        $estimation->update($request->all());

        return response()->json($estimation, 200);
    }

    public function destroy($id)
    {
        $estimation = Estimation::findOrFail($id);
        $estimation->delete();

        return response()->json(null, 204);
    }
}