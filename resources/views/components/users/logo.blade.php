@php
    $logo =staticBlockImage('logo','assets/images/logo.svg');
@endphp

<a href="{{ url('/') }}" class="flex-shrink-0">
    <div class="w-40 h-10 flex items-center justify-center overflow-hidden">
        <img src="{{ $logo }}" alt="Company Logo" class="mw-full h-full object-contain" loading="lazy">
    </div>
</a>
