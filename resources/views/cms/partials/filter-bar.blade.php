{{--
    ─────────────────────────────────────────────────────────────
    Filter Bar Component
    ─────────────────────────────────────────────────────────────
    Usage:
        @include('cms.partials.filter-bar', [
            'action' => route('cases.index'),
            'filters' => [
                [
                    'name' => 'search',
                    'type' => 'text',
                    'label' => 'بحث',
                    'placeholder' => 'رقم القضية، العنوان...',
                    'icon' => 'bi-search',
                    'col' => 'col-md-3',
                ],
                [
                    'name' => 'status',
                    'type' => 'select',
                    'label' => 'الحالة',
                    'options' => ['قيد النظر' => 'قيد النظر', ...],
                    'icon' => 'bi-flag',
                    'col' => 'col-md-2',
                ],
                [
                    'name' => 'opened',
                    'type' => 'date_range',
                    'label' => 'تاريخ الفتح',
                    'icon' => 'bi-calendar3',
                    'col' => 'col-md-2',
                ],
            ],
        ])
    ─────────────────────────────────────────────────────────────
--}}

@php
    $hasActiveFilters = collect(request()->only(array_column($filters ?? [], 'name')))
        ->filter(fn ($v) => filled($v))
        ->isNotEmpty();

    // فحص إضافي لحقول التاريخ (name_from / name_to)
    if (!$hasActiveFilters) {
        foreach ($filters ?? [] as $f) {
            if (($f['type'] ?? 'text') === 'date_range') {
                if (request()->filled($f['name'] . '_from') || request()->filled($f['name'] . '_to')) {
                    $hasActiveFilters = true;
                    break;
                }
            }
        }
    }
@endphp

<div class="card mb-3 border-0 shadow-sm">
    <div class="card-body py-3">

        <form action="{{ $action ?? url()->current() }}" method="GET" class="row g-2 align-items-end">

            @foreach ($filters as $filter)
                @php
                    $name = $filter['name'];
                    $type = $filter['type'] ?? 'text';
                    $label = $filter['label'] ?? $name;
                    $placeholder = $filter['placeholder'] ?? '';
                    $icon = $filter['icon'] ?? 'bi-funnel';
                    $value = request($name, $filter['default'] ?? '');
                    $colClass = $filter['col'] ?? 'col-md-3';
                @endphp

                <div class="{{ $colClass }}">
                    <label class="form-label small fw-semibold text-muted mb-1">
                        <i class="bi {{ $icon }} me-1"></i>
                        {{ $label }}
                    </label>

                    @if ($type === 'text')
                        <input type="text"
                               name="{{ $name }}"
                               value="{{ $value }}"
                               placeholder="{{ $placeholder }}"
                               class="form-control form-control-sm">

                    @elseif ($type === 'select')
                        <select name="{{ $name }}" class="form-select form-select-sm">
                            <option value="">— الكل —</option>
                            @foreach ($filter['options'] ?? [] as $key => $text)
                                <option value="{{ $key }}" @selected($value == $key)>{{ $text }}</option>
                            @endforeach
                        </select>

                    @elseif ($type === 'date')
                        <input type="date"
                               name="{{ $name }}"
                               value="{{ $value }}"
                               class="form-control form-control-sm">

                    @elseif ($type === 'date_range')
                        <div class="input-group input-group-sm">
                            <input type="date"
                                   name="{{ $name }}_from"
                                   value="{{ request($name.'_from') }}"
                                   class="form-control form-control-sm"
                                   title="من">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-arrow-left"></i>
                            </span>
                            <input type="date"
                                   name="{{ $name }}_to"
                                   value="{{ request($name.'_to') }}"
                                   class="form-control form-control-sm"
                                   title="إلى">
                        </div>
                    @endif
                </div>
            @endforeach

            {{-- أزرار البحث والمسح --}}
            <div class="col-md-auto">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="bi bi-search"></i>
                        بحث
                    </button>

                    @if ($hasActiveFilters)
                        <a href="{{ url()->current() }}"
                           class="btn btn-sm btn-outline-secondary"
                           title="مسح كل الفلاتر">
                            <i class="bi bi-x-circle"></i>
                            مسح
                        </a>
                    @endif
                </div>
            </div>

        </form>

        {{-- مؤشر النتائج --}}
        @if ($hasActiveFilters)
            <div class="mt-2 small text-muted">
                <i class="bi bi-funnel-fill text-warning"></i>
                يتم عرض النتائج المفلترة
                @if (isset($resultsCount))
                    ({{ $resultsCount }} نتيجة)
                @endif
            </div>
        @endif

    </div>
</div>