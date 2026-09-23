@extends('cms.parent')
@section('title', 'المحاكم')
@section('page-title', 'إدارة المحاكم')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center bg-white">
        <span class="fw-bold">قائمة المحاكم</span>
        <a href="{{ route('courts.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> محكمة جديدة</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th>اسم المحكمة</th><th>الاختصاص</th><th>الموقع</th><th>عدد القضايا</th><th class="text-center">الإجراءات</th></tr>
                </thead>
                <tbody>
                    @forelse ($courts as $court)
                        <tr>
                            <td class="fw-semibold">{{ $court->court_name }}</td>
                            <td>{{ $court->jurisdiction }}</td>
                            <td>{{ $court->location }}</td>
                            <td><span class="badge text-bg-light">{{ $court->cases_count }}</span></td>
                            <td class="text-center">
                                <a href="{{ route('courts.show', $court) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('courts.edit', $court) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('courts.destroy', $court) }}" method="POST" class="d-inline" onsubmit="return confirm('هل تريد حذف هذه المحكمة؟');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">لا توجد محاكم مسجلة.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($courts->hasPages())<div class="card-footer bg-white">{{ $courts->links() }}</div>@endif
</div>
@endsection
