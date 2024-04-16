<div {{ $attributes->merge(['class' => 'form-group mb-3 ' . $isHorizontal()]) }}>
    @if (!empty($label))
        <x-form::label :label="$label" :class="$labelCol()"/>
    @endif

@if ($isHorizontal())
    <div class="{{ $inputCol() }}">
@endif
    {!! $slot !!}
@if ($isHorizontal())
    </div>
@endif
</div>
