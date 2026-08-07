<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::query();

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $clients = $query->orderBy('id', 'desc')->paginate(24)->withQueryString();

        return view('admin.client.index', compact('clients'));
    }

    public function create()
    {
        return view('admin.client.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image'   => 'required|array|min:1',
            'image.*' => 'image|max:2048',
        ]);

        foreach ($request->file('image') as $file) {
            Client::create([
                'image'  => $file->store('client', 'public'),
                'status' => 'active',
            ]);
        }

        return redirect()->route('admin.client.index')->with('success', 'Client image(s) added successfully.');
    }

    public function edit(Client $client)
    {
        return view('admin.client.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $request->validate(['image' => 'nullable|image|max:2048']);

        if ($request->hasFile('image')) {
            if ($client->image) {
                Storage::disk('public')->delete($client->image);
            }
            $client->update(['image' => $request->file('image')->store('client', 'public')]);
        }

        return redirect()->route('admin.client.index')->with('success', 'Client image updated successfully.');
    }

    public function destroy(Client $client)
    {
        if ($client->image) {
            Storage::disk('public')->delete($client->image);
        }

        $client->delete();

        return response()->json(['message' => 'Client image deleted successfully.']);
    }
}