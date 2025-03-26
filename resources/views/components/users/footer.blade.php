@php
    $footerBlock =staticBlock('footer');
@endphp


@if ($footerBlock)
{!! htmlspecialchars_decode($footerBlock) !!}

@else
    <p class="text-center text-gray-500">No footer content available.</p>
@endif
