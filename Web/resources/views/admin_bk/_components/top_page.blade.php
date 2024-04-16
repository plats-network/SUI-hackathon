<!-- Title and Top Buttons Start -->
<div class="page-title-container mb-3">
    <div class="row">
        <!-- Title Start -->
        <div class="col mb-2">
            <h1 class="mb-2 pb-0 display-4" id="title">{{ $title }}</h1>
            <div class="text-muted font-heading text-small">
                {{ $desc ?? '' }}
            </div>
        </div>
        <!-- Title End -->
    @if (isset($slot) && $slot->isNotEmpty())
        <!-- Top Buttons Start -->
        <div class="col-12 col-sm-auto d-flex align-items-center justify-content-end">
            {{ $slot }}
        </div>
        <!-- Top Buttons End -->
    @endif
    </div>
</div>
<!-- Title and Top Buttons End -->
