@extends('cms.parent')
@section('title', $court->court_name)
@section('page-title', 'ملف المحكمة: '.$court->court_name)

@section('content')
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white fw-bold">بيانات المحكمة</div>
            <div class="card-body">
                <dl class="row mb-0 small">
                    <dt class="col-5 text-muted">الاسم</dt><dd class="col-7">{{ $court->court_name }}</dd>
                    <dt class="col-5 text-muted">الاختصاص</dt><dd class="col-7">{{ $court->jurisdiction }}</dd>
                    <dt class="col-5 text-muted">الموقع</dt><dd class="col-7">{{ $court->location }}</dd>
                </dl>
            </div>
            <div class="card-footer bg-white">
                <a href="{{ route('courts.edit', $court) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i> تعديل</a>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white fw-bold">القضايا في هذه المحكمة ({{ $court->cases->count() }})</div>
            <ul class="list-group list-group-flush">
                @forelse ($court->cases as $case)
                    <li class="list-group-item d-flex justify-content-between">
                        <a href="{{ route('cases.show', $case) }}">{{ $case->case_number }} — {{ $case->case_title }}</a>
                        <span class="badge text-bg-light">{{ $case->case_status }}</span>
                    </li>
                @empty
                    <li class="list-group-item text-muted">لا توجد قضايا مرتبطة بهذه المحكمة بعد.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
