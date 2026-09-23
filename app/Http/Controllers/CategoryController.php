<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::with('parent')->withCount('cases')->paginate(20);

        return view('cms.categories.index', compact('categories'));
    }

    public function create(): View
    {
        $categories = Category::all();

        return view('cms.categories.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'category_name' => 'required|string|max:45',
            'categories_id' => 'nullable|exists:categories,id',
        ]);

        Category::create($data);

        return redirect()->route('categories.index')->with('success', 'تمت إضافة التصنيف بنجاح.');
    }

    public function edit(Category $category): View
    {
        $categories = Category::where('id', '!=', $category->id)->get();

        return view('cms.categories.edit', compact('category', 'categories'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $data = $request->validate([
            'category_name' => 'required|string|max:45',
            'categories_id' => 'nullable|exists:categories,id|not_in:'.$category->id,
        ]);

        $category->update($data);

        return redirect()->route('categories.index')->with('success', 'تم تحديث التصنيف.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'تم حذف التصنيف.');
    }
}
