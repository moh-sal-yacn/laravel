@extends('cms.parent')
@section('title', 'المقالات')
@section('page-title', 'إدارة المقالات')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center bg-white">
        <span class="fw-bold">قائمة المقالات</span>
        <a href="{{ route('articles.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> مقال جديد</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th>العنوان</th><th>الكاتب</th><th>تاريخ النشر</th><th class="text-center">الإجراءات</th></tr>
                </thead>
                <tbody>
                    @forelse ($articles as $article)
                        <tr>
                            <td class="fw-semibold">{{ $article->title }}</td>
                            <td>{{ $article->author?->name ?? '—' }}</td>
                            <td>{{ optional($article->published_at)->format('Y-m-d') ?? 'غير منشور' }}</td>
                            <td class="text-center">
                                <a href="{{ route('articles.show', $article) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('articles.edit', $article) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('articles.destroy', $article) }}" method="POST" class="d-inline" onsubmit="return confirm('حذف هذا المقال؟');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">لا توجد مقالات.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($articles->hasPages())<div class="card-footer bg-white">{{ $articles->links() }}</div>@endif
</div>
@endsection
