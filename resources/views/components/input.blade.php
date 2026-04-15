@props(['label' => null, 'name' => null, 'value' => null, 'placeholder' => '', 'type' => 'text'])

<div class="mb-3">
    @if($label)
        <label class="form-label" for="{{ $name }}">{{ $label }}</label>
    @endif
    <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" placeholder="{{ $placeholder }}" value="{{ old($name, $value) }}" class="form-input">
    @error($name)
        <p class="small-text text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>
