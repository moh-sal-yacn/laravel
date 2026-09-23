@extends('cms.parent')
@section('title', 'تعديل تصنيف')
@section('page-title', 'تعديل التصنيف')

@section('content')
<div class="card col-lg-6">
    <div class="card-body">
        <form action="{{ route('categories.update', $category) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">اسم التصنيف</label>
                <input type="text" name="category_name" class="form-control" value="{{ old('category_name', $category->category_name) }}" maxlength="45" required>
            </div>
            <div class="mb-3">
                <label class="form-label">التصنيف الأب (اختياري)</label>
                <select name="categories_id" class="form-select">
                    <option value="">— بدون —</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('categories_id', $category->categories_id) == $cat->id)>{{ $cat->category_name }}</option>
                    @endforeach
                </select>
            </div>
            <button class="btn btn-primary">حفظ التعديلات</button>
            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">إلغاء</a>
        </form>
    </div>
</div>
@endsection
