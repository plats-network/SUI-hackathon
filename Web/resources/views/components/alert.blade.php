<div class="alert alert-{{ $type }}">
    @if (!$slot->isEmpty())
        {{ $slot }}
    @else
        @if (!is_array($messages))
            <span>{{ $messages }}</span>
        @else
            <ul class="mb-0">
                @foreach ($messages as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        @endif
    @endif
</div>
