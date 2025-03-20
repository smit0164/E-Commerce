<div id="{{ $modalId }}" class="fixed inset-0 bg-gray-900 bg-opacity-70 hidden flex items-center justify-center transition-opacity duration-300" data-modal>
    <div class="bg-white p-8 rounded-xl w-full max-w-md shadow-2xl transform transition-all duration-300 scale-95">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-900">{{ $title }}</h3>
            <button data-action="close-modal" data-modal="{{ $modalId }}" class="text-gray-500 hover:text-gray-700 transition">
                <i class="fas fa-times w-6 h-6"></i>
            </button>
        </div>
        {{ $slot }}
    </div>
</div>