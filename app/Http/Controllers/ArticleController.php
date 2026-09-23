<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(): View
    {
        $articles = Article::with('author')->latest('published_at')->paginate(15);

        return view('cms.articles.index', compact('articles'));
    }

    public function create(): View
    {
        return view('cms.articles.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:45',
            'content' => 'required|string',
            'published_at' => 'nullable|date',
        ]);

        $data['users_id'] = $request->user()->id ?? 1;

        Article::create($data);

        return redirect()->route('articles.index')->with('success', 'تم نشر المقال بنجاح.');
    }

    public function show(Article $article): View
    {
        $article->load('author');

        return view('cms.articles.show', compact('article'));
    }

    public function edit(Article $article): View
    {
        return view('cms.articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:45',
            'content' => 'required|string',
            'published_at' => 'nullable|date',
        ]);

        $article->update($data);

        return redirect()->route('articles.show', $article)->with('success', 'تم تحديث المقال.');
    }

    public function destroy(Article $article): RedirectResponse
    {
        $article->delete();

        return redirect()->route('articles.index')->with('success', 'تم حذف المقال.');
    }
}
