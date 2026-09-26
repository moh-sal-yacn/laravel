<?php

namespace App\Http\Controllers;

use App\Http\Requests\Client\UpdateClientRequest;
use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    /**
     * عرض قائمة الموكلين (مع تصفية حسب الدور + الفلاتر المتقدمة)
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Client::class);

        $user = auth()->user();
        $query = Client::with('user');

        // ─── الموكل: يرى بياناته فقط ───
        if ($user->isClient()) {
            $query->where('users_id', $user->id);
        }

        // ─── المحامي: يرى موكليه فقط ───
        if ($user->isLawyer()) {
            $query->where(function ($q) use ($user) {
                $q->whereHas('cases.participants', fn ($sub) => $sub->where('users_id', $user->id))
                  ->orWhereHas('contracts', fn ($sub) => $sub->where('users_id', $user->id));
            });
        }

        // ─── الفلاتر المتقدمة ───
        $clients = $query
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($sub) use ($search) {
                    $sub->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%")
                                                       ->orWhere('email', 'like', "%{$search}%"))
                        ->orWhere('national_id', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('kind'), fn ($q) => $q->where('client_kind', $request->kind))
            ->when($request->filled('national_id'), fn ($q) => $q->where('national_id', 'like', "%{$request->national_id}%"))
            ->when($request->filled('registered_from'), fn ($q) => $q->whereDate('created_at', '>=', $request->registered_from))
            ->when($request->filled('registered_to'), fn ($q) => $q->whereDate('created_at', '<=', $request->registered_to))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('cms.clients.index', compact('clients'));
    }

    public function show(Client $client): View
    {
        $this->authorize('view', $client);
        $client->load(['user', 'cases', 'contracts']);
        return view('cms.clients.show', compact('client'));
    }

    public function edit(Client $client): View
    {
        $this->authorize('update', $client);
        $client->load('user');
        return view('cms.clients.edit', compact('client'));
    }

    public function update(UpdateClientRequest $request, Client $client): RedirectResponse
    {
        $this->authorize('update', $client);
        $client->update($request->validated());

        return redirect()->route('clients.show', $client)
            ->with('success', 'تم تحديث بيانات الموكل بنجاح.');
    }

    public function destroy(Client $client): RedirectResponse
    {
        $this->authorize('delete', $client);
        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'تم حذف الموكل بنجاح. يمكنك استرجاعه من صفحة المحذوفات.');
    }

    public function trashed(): View
    {
        $clients = Client::onlyTrashed()
            ->with('user')
            ->latest('deleted_at')
            ->paginate(20);

        return view('cms.clients.trashed', compact('clients'));
    }

    public function restore(int $id): RedirectResponse
    {
        $client = Client::onlyTrashed()->findOrFail($id);
        $client->restore();

        return redirect()->route('clients.trashed')
            ->with('success', "تم استرجاع الموكل {$client->user?->name} بنجاح.");
    }

    public function forceDelete(int $id): RedirectResponse
    {
        $client = Client::onlyTrashed()->findOrFail($id);
        $name = $client->user?->name;
        $client->forceDelete();

        return redirect()->route('clients.trashed')
            ->with('success', "تم حذف الموكل {$name} نهائياً.");
    }
}