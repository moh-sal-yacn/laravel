<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(): View
    {
        $clients = Client::with('user')->paginate(20);

        return view('cms.clients.index', compact('clients'));
    }

    public function show(Client $client): View
    {
        $client->load(['user', 'cases', 'contracts']);

        return view('cms.clients.show', compact('client'));
    }

    public function edit(Client $client): View
    {
        $client->load('user');

        return view('cms.clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client): RedirectResponse
    {
        $data = $request->validate([
            'client_kind' => 'required|in:individual,company',
            'national_id' => 'nullable|string|max:45',
        ]);

        $client->update($data);

        return redirect()->route('clients.show', $client)->with('success', 'تم تحديث بيانات الموكل.');
    }

    public function destroy(Client $client): RedirectResponse
    {
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'تم حذف الموكل.');
    }
}
