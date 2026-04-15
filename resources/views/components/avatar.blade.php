@props(['name' => null, 'src' => null, 'size' => '10'])

@if($src)
    <img src="{{ $src }}" alt="{{ $name }}" class="rounded-full" style="width: calc({{ $size }} * 0.25rem); height: calc({{ $size }} * 0.25rem);" />
@else
    <div class="rounded-full bg-gray-200 text-gray-700 font-semibold flex items-center justify-center" style="width: calc({{ $size }} * 0.25rem); height: calc({{ $size }} * 0.25rem);">
        {{ $name ? strtoupper(substr($name,0,1)) : '?' }}
    </div>
@endif
