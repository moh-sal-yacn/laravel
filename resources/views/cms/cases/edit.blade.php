@extends('cms.parent')
@section('title', 'تعديل قضية')
@section('page-title', 'تعديل القضية: '.$case->case_number)

@section('content')
<div class="card col-lg-7">
    <div class="card-body">
        <form action="{{ route('cases.update', $case) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">رقم القضية</label>
                    <input type="text" name="case_number" class="form-control" value="{{ old('case_number', $case->case_number) }}" maxlength="45" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">عنوان القضية</label>
                    <input type="text" name="case_title" class="form-control" value="{{ old('case_title', $case->case_title) }}" maxlength="45" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">الحالة</label>
                    <select name="case_status" class="form-select" required>
                        @foreach (['قيد النظر', 'مؤجلة', 'منتهية', 'مؤرشفة'] as $status)
                            <option value="{{ $status }}" @selected(old('case_status', $case->case_status) === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">تاريخ الفتح</label>
                    <input type="date" name="opened_at" class="form-control" value="{{ old('opened_at', $case->opened_at->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">الموكل</label>
                    <select name="clients_id" class="form-select" required>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}" @selected(old('clients_id', $case->clients_id) == $client->id)>{{ $client->user?->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">المحكمة</label>
                    <select name="courts_id" class="form-select" required>
                        @foreach ($courts as $court)
                            <option value="{{ $court->id }}" @selected(old('courts_id', $case->courts_id) == $court->id)>{{ $court->court_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">التصنيف (اختياري)</label>
                    <select name="categories_id" class="form-select">
                        <option value="">— بدون —</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('categories_id', $case->categories_id) == $category->id)>{{ $category->category_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">الوصف</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $case->description) }}</textarea>
                </div>
            </div>
            <div class="mt-3">
                <button class="btn btn-primary">حفظ التعديلات</button>
                <a href="{{ route('cases.show', $case) }}" class="btn btn-outline-secondary">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection
