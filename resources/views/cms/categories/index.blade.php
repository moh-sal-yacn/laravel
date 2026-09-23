@extends('cms.parent')
@section('title', 'التصنيفات')
@section('page-title', 'تصنيفات القضايا')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center bg-white">
        <span class="fw-bold">قائمة التصنيفات</span>
        <a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> تصنيف جديد</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th>اسم التصنيف</th><th>التصنيف الأب</th><th>عدد القضايا</th><th class="text-center">الإجراءات</th></tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td class="fw-semibold">{{ $category->category_name }}</td>
                            <td>{{ $category->parent?->category_name ?? '—' }}</td>
                            <td><span class="badge text-bg-light">{{ $category->cases_count }}</span></td>
                            <td class="text-center">
                                <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('هل تريد حذف هذا التصنيف؟');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">لا توجد تصنيفات مسجلة.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($categories->hasPages())<div class="card-footer bg-white">{{ $categories->links() }}</div>@endif
</div>
@endsection
