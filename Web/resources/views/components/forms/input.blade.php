<input name="{{ $name }}" type="{{ $type }}" {{ $attributes->merge(['class' => 'form-control']) }}
data-rule-maxlength="{{ INPUT_MAX_LENGTH}}" value="{{ $value }}">
