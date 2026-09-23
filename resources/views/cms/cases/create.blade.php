@extends('cms.parent')
@section('title', 'قضية جديدة')
@section('page-title', 'فتح قضية جديدة')

@section('content')
<div class="card col-lg-7">
    <div class="card-body">
        <form action="{{ route('cases.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">رقم القضية</label>
                    <input type="text" name="case_number" class="form-control" value="{{ old('case_number') }}" maxlength="45" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">عنوان القضية</label>
                    <input type="text" name="case_title" class="form-control" value="{{ old('case_title') }}" maxlength="45" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">الحالة</label>
                    <select name="case_status" class="form-select" required>
                        <option value="قيد النظر">قيد النظر</option>
                        <option value="مؤجلة">مؤجلة</option>
                        <option value="منتهية">منتهية</option>
                        <option value="مؤرشفة">مؤرشفة</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">تاريخ الفتح</label>
                    <input type="date" name="opened_at" class="form-control" value="{{ old('opened_at', now()->toDateString()) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">الموكل</label>
                    <select name="clients_id" class="form-select" required>
                        <option value="">— اختر —</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->user?->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">المحكمة</label>
                    <select name="courts_id" class="form-select" required>
                        <option value="">— اختر —</option>
                        @foreach ($courts as $court)
                            <option value="{{ $court->id }}">{{ $court->court_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">التصنيف (اختياري)</label>
                    <select name="categories_id" class="form-select">
                        <option value="">— بدون —</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">الوصف</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>
            </div>
            <div class="mt-3">
                <button class="btn btn-primary">حفظ</button>
                <a href="{{ route('cases.index') }}" class="btn btn-outline-secondary">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection
