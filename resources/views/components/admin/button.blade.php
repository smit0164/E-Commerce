<button {{ $attributes->merge(['class' => 'px-5 py-2.5 rounded-lg shadow-md transition duration-200 flex items-center']) }}>
    @if($icon ?? false)
        <i class="{{ $icon }} mr-2"></i>
    @endif
    {{ $slot }}
</button>