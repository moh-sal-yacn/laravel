@extends('cms.parent')
@section('title', $user->name)
@section('page-title', 'ملف المستخدم: '.$user->name)

@section('content')
<div class="row g-3">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header bg-white fw-bold">بيانات المستخدم</div>
            <div class="card-body">
                <dl class="row mb-0 small">
                    <dt class="col-5 text-muted">الاسم</dt><dd class="col-7">{{ $user->name }}</dd>
                    <dt class="col-5 text-muted">البريد</dt><dd class="col-7">{{ $user->email }}</dd>
                    <dt class="col-5 text-muted">الهاتف</dt><dd class="col-7">{{ $user->phone ?? '—' }}</dd>
                    <dt class="col-5 text-muted">الحالة</dt>
                    <dd class="col-7">
                        @if ($user->is_active)<span class="badge text-bg-success">مفعّل</span>@else<span class="badge text-bg-danger">موقوف</span>@endif
                    </dd>
                    <dt class="col-5 text-muted">الأدوار</dt>
                    <dd class="col-7">
                        @foreach ($user->roles as $role)<span class="badge text-bg-secondary">{{ $role->role_name }}</span>@endforeach
                    </dd>
                </dl>
                @if ($user->profile)
                    <hr>
                    <div class="small text-muted">{{ $user->profile->bio }}</div>
                @endif
            </div>
            <div class="card-footer bg-white">
                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i> تعديل</a>
            </div>
        </div>
    </div>
</div>
@endsection
