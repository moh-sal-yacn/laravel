@extends('cms.parent')
@section('title', 'رسائل التواصل')
@section('page-title', 'رسائل ونماذج التواصل')

@php $statusColors = ['جديد' => 'primary', 'قيد المعالجة' => 'warning', 'مغلق' => 'secondary']; @endphp

@section('content')
<div class="card">
    <div class="card-header bg-white fw-bold">قائمة الرسائل</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th>القناة</th><th>الرسالة</th><th>المرسل</th><th>التاريخ</th><th>الحالة</th><th class="text-center">الإجراءات</th></tr>
                </thead>
                <tbody>
                    @forelse ($messages as $message)
                        <tr>
                            <td><span class="badge text-bg-light">{{ $message->channel_type }}</span></td>
                            <td class="text-muted small" style="max-width:280px;">{{ \Illuminate\Support\Str::limit($message->message, 70) }}</td>
                            <td>{{ $message->user?->name ?? '—' }}</td>
                            <td>{{ optional($message->created_at)->format('Y-m-d H:i') }}</td>
                            <td>
                                <form action="{{ route('contact-channels.update', $message) }}" method="POST" class="d-flex gap-1">
                                    @csrf @method('PUT')
                                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                        @foreach (['جديد', 'قيد المعالجة', 'مغلق'] as $status)
                                            <option value="{{ $status }}" @selected($message->status === $status)>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td class="text-center">
                                <form action="{{ route('contact-channels.destroy', $message) }}" method="POST" onsubmit="return confirm('حذف هذه الرسالة؟');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">لا توجد رسائل واردة.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($messages->hasPages())<div class="card-footer bg-white">{{ $messages->links() }}</div>@endif
</div>
@endsection
