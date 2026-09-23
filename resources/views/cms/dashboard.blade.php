@extends('cms.parent')

@section('title', 'لوحة التحكم')
@section('page-title', 'لوحة التحكم الرئيسية')

@section('content')
    <div class="row g-3">
        <div class="col-md-3">
            <div class="card p-3">
                <div class="text-muted small">إجمالي القضايا</div>
                <div class="fs-3 fw-bold">{{ \App\Models\CourtCase::count() }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3">
                <div class="text-muted small">العقود النشطة</div>
                <div class="fs-3 fw-bold">{{ \App\Models\Contract::where('contract_status', 'نشط')->count() }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3">
                <div class="text-muted small">الموكلون</div>
                <div class="fs-3 fw-bold">{{ \App\Models\Client::count() }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3">
                <div class="text-muted small">طلبات الحجز المعلّقة</div>
                <div class="fs-3 fw-bold">{{ \App\Models\Booking::where('status', 'قيد الانتظار')->count() }}</div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('cases.index') }}" class="btn btn-outline-primary">عرض القضايا</a>
        <a href="{{ route('contracts.index') }}" class="btn btn-outline-secondary">عرض العقود</a>
    </div>
@endsection
