@extends('cms.parent')
@section('title', $article->title)
@section('page-title', $article->title)

@section('content')
<div class="card col-lg-8">
    <div class="card-body">
        <div class="text-muted small mb-3">
            بقلم: {{ $article->author?->name ?? '—' }} — {{ optional($article->published_at)->format('Y-m-d') ?? 'مسودة' }}
        </div>
        <div class="lh-lg">{!! nl2br(e($article->content)) !!}</div>
    </div>
    <div class="card-footer bg-white">
        <a href="{{ route('articles.edit', $article) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i> تعديل</a>
    </div>
</div>
@endsection
