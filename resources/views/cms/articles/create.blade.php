@extends('cms.parent')
@section('title', 'مقال جديد')
@section('page-title', 'كتابة مقال جديد')

@section('content')
<div class="card col-lg-8">
    <div class="card-body">
        <form action="{{ route('articles.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">العنوان</label>
                <input type="text" name="title" class="form-control" value="{{ old('title') }}" maxlength="45" required>
            </div>
            <div class="mb-3">
                <label class="form-label">المحتوى</label>
                <textarea name="content" class="form-control" rows="8" required>{{ old('content') }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">تاريخ النشر (اتركه فارغًا لحفظه كمسودة)</label>
                <input type="date" name="published_at" class="form-control" value="{{ old('published_at') }}">
            </div>
            <button class="btn btn-primary">نشر</button>
            <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary">إلغاء</a>
        </form>
    </div>
</div>
@endsection
