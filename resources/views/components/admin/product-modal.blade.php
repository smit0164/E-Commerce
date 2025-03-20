<div id="{{ $modalId }}" class="fixed inset-0 bg-gray-900 bg-opacity-70 hidden flex items-center justify-center transition-opacity duration-300" data-modal>
    <div class="bg-white p-6 rounded-lg w-full max-w-3xl shadow-2xl transform transition-all duration-300 scale-95">
        <div class="flex justify-between items-center mb-4 border-b pb-3">
            <h3 class="text-xl font-bold text-gray-900">{{ $title }}</h3>
            <button type="button" data-action="close-modal" data-modal="{{ $modalId }}" class="text-gray-500 hover:text-gray-700 transition">
                <i class="fas fa-times w-6 h-6"></i>
            </button>
        </div>

        <!-- Two-Column Layout -->
        <div class="grid grid-cols-2 gap-6 p-4">
            <!-- Left Column -->
            <div class="space-y-4">
                {{ $left }}
            </div>

            <!-- Right Column -->
            <div class="space-y-4">
                {{ $right }}
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex justify-end space-x-3 mt-6 border-t pt-4">
            <x-admin.button type="button" data-action="close-modal" data-modal="{{ $modalId }}" class="bg-gray-200 text-gray-700 hover:bg-gray-300">Cancel</x-admin.button>
            <x-admin.button type="submit" class="bg-indigo-600 text-white hover:bg-indigo-700">{{$button}}</x-admin.button>
        </div>
    </div>
</div>
