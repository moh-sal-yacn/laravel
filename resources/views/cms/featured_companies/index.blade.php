@extends('cms.parent')
@section('title', 'الشركات المميزة')
@section('page-title', 'الشركات المميزة والشركاء')

@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white fw-bold">قائمة الشركات</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr><th>اسم الشركة</th><th>تاريخ التعاقد</th><th>الوصف</th><th class="text-center">الإجراءات</th></tr>
                        </thead>
                        <tbody>
                            @forelse ($companies as $company)
                                <tr>
                                    <td class="fw-semibold">{{ $company->company_name }}</td>
                                    <td>{{ optional($company->contract_date)->format('Y-m-d') ?? '—' }}</td>
                                    <td class="text-muted small">{{ \Illuminate\Support\Str::limit($company->description, 60) }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('featured-companies.destroy', $company) }}" method="POST" onsubmit="return confirm('حذف هذه الشركة؟');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-4">لا توجد شركات مضافة.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($companies->hasPages())<div class="card-footer bg-white">{{ $companies->links() }}</div>@endif
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white fw-bold">إضافة شركة جديدة</div>
            <div class="card-body">
                <form action="{{ route('featured-companies.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">اسم الشركة</label>
                        <input type="text" name="company_name" class="form-control" maxlength="45" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">تاريخ التعاقد</label>
                        <input type="date" name="contract_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الوصف</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <button class="btn btn-primary w-100">حفظ</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
