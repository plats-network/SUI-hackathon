<form id="{{ $attributes->get('id', 'form_' . Str::random(5)) }}"
      {{ $attributes->merge(['class' => 'has_validate'])  }}
      method="{{ $attributes->get('method', 'POST') }}"
      action="{{ $attributes->get('action') }}"
        {{ $attributes->has('files') ? 'enctype=multipart/form-data' : '' }}>
    @if(in_array($attributes->get('method', 'POST'), ['POST', 'PUT']))
        @csrf
    @endif

    {{ $slot }}
</form>
