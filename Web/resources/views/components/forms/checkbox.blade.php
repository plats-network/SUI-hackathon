@foreach($list as $value => $label)
<div class="custom-control custom-checkbox">
    <input type="checkbox" class="custom-control-input" value="{{ $value }}" id="{{ $value .'_'. $loop->iteration }}">
    <label class="custom-control-label" for="{{ $value .'_'. $loop->iteration }}">{{ $label }}</label>
</div>
@endforeach
