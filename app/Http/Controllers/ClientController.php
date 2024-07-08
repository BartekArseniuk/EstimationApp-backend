<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        return Client::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
        ]);

        $client = Client::create($request->all());
        return response()->json($client, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $id,
        ]);

        $client = Client::findOrFail($id);
        $client->update($request->all());
        return response()->json($client, 200);
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();
        return response()->json(null, 204);
    }
}