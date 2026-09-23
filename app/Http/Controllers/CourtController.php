<?php

namespace App\Http\Controllers;

use App\Models\Court;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourtController extends Controller
{
    public function index(): View
    {
        $courts = Court::withCount('cases')->paginate(15);

        return view('cms.courts.index', compact('courts'));
    }

    public function create(): View
    {
        return view('cms.courts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'court_name' => 'required|string|max:45',
            'jurisdiction' => 'required|string|max:45',
            'location' => 'required|string|max:45',
        ]);

        Court::create($data);

        return redirect()->route('courts.index')->with('success', 'تمت إضافة المحكمة بنجاح.');
    }

    public function show(Court $court): View
    {
        $court->load('cases');

        return view('cms.courts.show', compact('court'));
    }

    public function edit(Court $court): View
    {
        return view('cms.courts.edit', compact('court'));
    }

    public function update(Request $request, Court $court): RedirectResponse
    {
        $data = $request->validate([
            'court_name' => 'required|string|max:45',
            'jurisdiction' => 'required|string|max:45',
            'location' => 'required|string|max:45',
        ]);

        $court->update($data);

        return redirect()->route('courts.index')->with('success', 'تم تحديث بيانات المحكمة.');
    }

    public function destroy(Court $court): RedirectResponse
    {
        $court->delete();

        return redirect()->route('courts.index')->with('success', 'تم حذف المحكمة.');
    }
}
