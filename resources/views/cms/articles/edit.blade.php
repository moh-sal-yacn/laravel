@extends('cms.parent')
@section('title', 'تعديل مقال')
@section('page-title', 'تعديل المقال')

@section('content')
<div class="card col-lg-8">
    <div class="card-body">
        <form action="{{ route('articles.update', $article) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">العنوان</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $article->title) }}" maxlength="45" required>
            </div>
            <div class="mb-3">
                <label class="form-label">المحتوى</label>
                <textarea name="content" class="form-control" rows="8" required>{{ old('content', $article->content) }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">تاريخ النشر</label>
                <input type="date" name="published_at" class="form-control" value="{{ old('published_at', optional($article->published_at)->format('Y-m-d')) }}">
            </div>
            <button class="btn btn-primary">حفظ التعديلات</button>
            <a href="{{ route('articles.show', $article) }}" class="btn btn-outline-secondary">إلغاء</a>
        </form>
    </div>
</div>
@endsection
