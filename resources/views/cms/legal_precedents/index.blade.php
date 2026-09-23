@extends('cms.parent')
@section('title', 'السوابق القضائية')
@section('page-title', 'مكتبة السوابق القضائية')

@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white fw-bold">قائمة السوابق</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr><th>العنوان</th><th>المصدر</th><th>تاريخ الحكم</th><th>القضية المرتبطة</th><th class="text-center">الإجراءات</th></tr>
                        </thead>
                        <tbody>
                            @forelse ($precedents as $precedent)
                                <tr>
                                    <td class="fw-semibold">{{ $precedent->title }}</td>
                                    <td><span class="badge text-bg-light">{{ $precedent->source }}</span></td>
                                    <td>{{ optional($precedent->ruling_date)->format('Y-m-d') }}</td>
                                    <td>
                                        @if ($precedent->case)
                                            <a href="{{ route('cases.show', $precedent->case) }}">{{ $precedent->case->case_number }}</a>
                                        @else — @endif
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('legal-precedents.destroy', $precedent) }}" method="POST" onsubmit="return confirm('حذف هذه السابقة؟');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-4">لا توجد سوابق قضائية مضافة.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($precedents->hasPages())<div class="card-footer bg-white">{{ $precedents->links() }}</div>@endif
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white fw-bold">إضافة سابقة قضائية</div>
            <div class="card-body">
                <form action="{{ route('legal-precedents.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">العنوان</label>
                        <input type="text" name="title" class="form-control" maxlength="45" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">المصدر</label>
                        <select name="source" class="form-select" required>
                            <option value="محكمة النقض">محكمة النقض</option>
                            <option value="محكمة الاستئناف">محكمة الاستئناف</option>
                            <option value="محكمة عليا">محكمة عليا</option>
                            <option value="أخرى">أخرى</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">تاريخ الحكم</label>
                        <input type="date" name="ruling_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">القضية المرتبطة (اختياري)</label>
                        <select name="cases_id" class="form-select">
                            <option value="">— بدون —</option>
                            @foreach ($cases as $case)<option value="{{ $case->id }}">{{ $case->case_number }} — {{ $case->case_title }}</option>@endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">رابط خارجي (اختياري)</label>
                        <input type="url" name="external_link" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الملخص</label>
                        <textarea name="summary" class="form-control" rows="3" required></textarea>
                    </div>
                    <button class="btn btn-primary w-100">حفظ</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
