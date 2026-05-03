<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::where('user_id', auth()->id())
            ->withCount('videoProjects')
            ->latest()
            ->get();

        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'nullable|string|max:50',
            'company' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'notes'   => 'nullable|string|max:2000',
        ]);

        $client = Client::create(array_merge($data, ['user_id' => auth()->id()]));

        return redirect()->route('clients.show', $client)->with('success', 'Client created successfully.');
    }

    public function show(Client $client)
    {
        abort_if($client->user_id !== auth()->id(), 403);

        $client->load(['videoProjects' => fn($q) => $q->withCount(['scripts', 'assets'])->latest()]);

        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        abort_if($client->user_id !== auth()->id(), 403);
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        abort_if($client->user_id !== auth()->id(), 403);

        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'nullable|string|max:50',
            'company' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'notes'   => 'nullable|string|max:2000',
        ]);

        $client->update($data);

        return redirect()->route('clients.show', $client)->with('success', 'Client updated.');
    }

    public function destroy(Client $client)
    {
        abort_if($client->user_id !== auth()->id(), 403);
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Client deleted.');
    }
}
