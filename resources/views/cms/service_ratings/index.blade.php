@extends('cms.parent')
@section('title', 'تقييمات الخدمة')
@section('page-title', 'تقييمات الخدمة')

@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white fw-bold">قائمة التقييمات</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr><th>الموكل</th><th>التقييم</th><th>التعليق</th><th>القضية</th><th>التصنيف</th><th class="text-center">الإجراءات</th></tr>
                        </thead>
                        <tbody>
                            @forelse ($ratings as $rating)
                                <tr>
                                    <td class="fw-semibold">{{ $rating->client?->user?->name ?? '—' }}</td>
                                    <td>
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="bi {{ $i <= $rating->rating ? 'bi-star-fill text-warning' : 'bi-star text-muted' }}"></i>
                                        @endfor
                                    </td>
                                    <td class="text-muted small">{{ \Illuminate\Support\Str::limit($rating->comment, 50) }}</td>
                                    <td>{{ $rating->case?->case_number ?? '—' }}</td>
                                    <td>{{ $rating->category?->category_name ?? '—' }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('service-ratings.destroy', $rating) }}" method="POST" onsubmit="return confirm('حذف هذا التقييم؟');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted py-4">لا توجد تقييمات مسجلة.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($ratings->hasPages())<div class="card-footer bg-white">{{ $ratings->links() }}</div>@endif
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white fw-bold">إضافة تقييم يدويًا</div>
            <div class="card-body">
                <form action="{{ route('service-ratings.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">الموكل</label>
                        <select name="clients_id" class="form-select" required>
                            <option value="">— اختر —</option>
                            @foreach ($clients as $client)<option value="{{ $client->id }}">{{ $client->user?->name }}</option>@endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">التقييم</label>
                        <select name="rating" class="form-select" required>
                            @for ($i = 5; $i >= 1; $i--)<option value="{{ $i }}">{{ $i }} نجوم</option>@endfor
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">القضية (اختياري)</label>
                        <select name="cases_id" class="form-select">
                            <option value="">— بدون —</option>
                            @foreach ($cases as $case)<option value="{{ $case->id }}">{{ $case->case_number }}</option>@endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">التصنيف (اختياري)</label>
                        <select name="categories_id" class="form-select">
                            <option value="">— بدون —</option>
                            @foreach ($categories as $category)<option value="{{ $category->id }}">{{ $category->category_name }}</option>@endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الحجز (اختياري)</label>
                        <select name="bookings_id" class="form-select">
                            <option value="">— بدون —</option>
                            @foreach ($bookings as $booking)<option value="{{ $booking->id }}">{{ $booking->visitor_name }}</option>@endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">التعليق</label>
                        <textarea name="comment" class="form-control" rows="2"></textarea>
                    </div>
                    <button class="btn btn-primary w-100">حفظ</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
