@extends('cms.parent')
@section('title', 'تعديل محكمة')
@section('page-title', 'تعديل بيانات المحكمة')

@section('content')
<div class="card col-lg-6">
    <div class="card-body">
        <form action="{{ route('courts.update', $court) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">اسم المحكمة</label>
                <input type="text" name="court_name" class="form-control" value="{{ old('court_name', $court->court_name) }}" maxlength="45" required>
            </div>
            <div class="mb-3">
                <label class="form-label">الاختصاص</label>
                <input type="text" name="jurisdiction" class="form-control" value="{{ old('jurisdiction', $court->jurisdiction) }}" maxlength="45" required>
            </div>
            <div class="mb-3">
                <label class="form-label">الموقع</label>
                <input type="text" name="location" class="form-control" value="{{ old('location', $court->location) }}" maxlength="45" required>
            </div>
            <button class="btn btn-primary">حفظ التعديلات</button>
            <a href="{{ route('courts.index') }}" class="btn btn-outline-secondary">إلغاء</a>
        </form>
    </div>
</div>
@endsection
