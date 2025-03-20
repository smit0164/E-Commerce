<div class="overflow-x-auto rounded-lg shadow-sm border border-gray-200">
    <table class="min-w-full bg-white" id="{{ $tableId }}">
        <thead class="bg-gray-100 text-gray-600">
            <tr>
                @foreach($columns as $column)
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">{{ $column }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 text-gray-700" id="{{ $tbodyId }}">
            {{ $slot }}
        </tbody>
    </table>
</div>