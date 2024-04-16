<a
    href="{{ $url ?? '#' }}"
    data-bs-toggle="tooltip"
    data-bs-placement="top"
    class="px-2 text-primary"
    data-bs-original-title="{{$label ?? 'Show'}}"
    aria-label="{{$label ?? 'Show'}}">
        <i class="bx bx-{{$icon ?? 'show'}} font-size-18"></i>
</a>