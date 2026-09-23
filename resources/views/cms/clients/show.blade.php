@extends('cms.parent')
@section('title', $client->user?->name)
@section('page-title', 'ملف الموكل: '.$client->user?->name)

@section('content')
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white fw-bold">بيانات الموكل</div>
            <div class="card-body">
                <dl class="row mb-0 small">
                    <dt class="col-5 text-muted">الاسم</dt><dd class="col-7">{{ $client->user?->name }}</dd>
                    <dt class="col-5 text-muted">البريد</dt><dd class="col-7">{{ $client->user?->email }}</dd>
                    <dt class="col-5 text-muted">الهاتف</dt><dd class="col-7">{{ $client->user?->phone ?? '—' }}</dd>
                    <dt class="col-5 text-muted">النوع</dt><dd class="col-7">{{ $client->client_kind === 'company' ? 'شركة' : 'فرد' }}</dd>
                    <dt class="col-5 text-muted">الرقم الوطني</dt><dd class="col-7">{{ $client->national_id ?? '—' }}</dd>
                </dl>
            </div>
            <div class="card-footer bg-white">
                <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i> تعديل</a>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header bg-white fw-bold">القضايا ({{ $client->cases->count() }})</div>
            <ul class="list-group list-group-flush">
                @forelse ($client->cases as $case)
                    <li class="list-group-item d-flex justify-content-between">
                        <a href="{{ route('cases.show', $case) }}">{{ $case->case_number }} — {{ $case->case_title }}</a>
                        <span class="badge text-bg-light">{{ $case->case_status }}</span>
                    </li>
                @empty
                    <li class="list-group-item text-muted">لا توجد قضايا لهذا الموكل.</li>
                @endforelse
            </ul>
        </div>
        <div class="card">
            <div class="card-header bg-white fw-bold">العقود ({{ $client->contracts->count() }})</div>
            <ul class="list-group list-group-flush">
                @forelse ($client->contracts as $contract)
                    <li class="list-group-item d-flex justify-content-between">
                        <a href="{{ route('contracts.show', $contract) }}">{{ $contract->contract_type }} — {{ number_format($contract->contract_value, 2) }}</a>
                        <span class="badge text-bg-light">{{ $contract->contract_status }}</span>
                    </li>
                @empty
                    <li class="list-group-item text-muted">لا توجد عقود لهذا الموكل.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
