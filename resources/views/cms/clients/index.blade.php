@extends('cms.parent')
@section('title', 'الموكلون')
@section('page-title', 'إدارة الموكلين')

@section('content')
<div class="card">
    <div class="card-header bg-white fw-bold">قائمة الموكلين</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th>الاسم</th><th>البريد الإلكتروني</th><th>النوع</th><th>الرقم الوطني</th><th class="text-center">الإجراءات</th></tr>
                </thead>
                <tbody>
                    @forelse ($clients as $client)
                        <tr>
                            <td class="fw-semibold">{{ $client->user?->name }}</td>
                            <td>{{ $client->user?->email }}</td>
                            <td>
                                <span class="badge text-bg-light">{{ $client->client_kind === 'company' ? 'شركة' : 'فرد' }}</span>
                            </td>
                            <td>{{ $client->national_id ?? '—' }}</td>
                            <td class="text-center">
                                <a href="{{ route('clients.show', $client) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('clients.destroy', $client) }}" method="POST" class="d-inline" onsubmit="return confirm('هل تريد حذف هذا الموكل؟');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">لا يوجد موكلون مسجلون.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($clients->hasPages())<div class="card-footer bg-white">{{ $clients->links() }}</div>@endif
</div>
@endsection
