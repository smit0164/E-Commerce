@props(['title', 'value', 'icon' => 'fa-question', 'color' => 'bg-gray-100 text-gray-600'])

<div class="{{ $color }} p-4 rounded-lg shadow-md flex items-center">
    <i class="fas {{ $icon }} text-2xl mr-3"></i>
    <div>
        <h3 class="text-sm font-medium text-gray-700">{{ $title }}</h3>
        <p class="text-lg font-bold">{{ $value }}</p>
    </div>
</div>
