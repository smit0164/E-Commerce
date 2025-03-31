<!-- _product_table.blade.php -->
<div class="overflow-x-auto">
    <table class="w-full divide-y divide-gray-200 table-auto">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky right-0 bg-gray-50">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($products as $product)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-5">{{ $product->id }}</td>
                    <td class="px-4 py-5">
                        @if ($product->image)
                            <img src="{{ $product->getProductImageUrl() }}" alt="{{ $product->name }}" class="max-w-[50px] h-auto">
                        @else
                            <span class="text-gray-500">No Image</span>
                        @endif
                    </td>
                    <td class="px-4 py-5 truncate max-w-xs">{{ $product->name }}</td>
                    <td class="px-4 py-5 truncate max-w-xs">{{ $product->categories->pluck('name')->implode(', ') ?: 'No Categories' }}</td>
                    <td class="px-4 py-5">₹{{ number_format($product->price, 2) }}</td>
                    <td class="px-4 py-5">{{ $product->quantity }}</td>
                    <td class="px-4 py-5">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $product->status==='active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $product->status==='active' ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-4 py-5">{{ $product->created_at->format('M d, Y') }}</td>
                    <td class="px-4 py-5 flex space-x-3 sticky right-0 bg-white shadow-[inset_10px_0_10px_-10px_rgba(0,0,0,0.1)]">
                        <a href="{{ route('admin.products.show', $product->slug) }}" class="text-green-600 hover:text-green-800" title="View Product">
                            <i class="fas fa-eye w-5 h-5"></i>
                        </a>
                        <a href="{{ route('admin.products.edit', $product->slug) }}" class="text-indigo-600 hover:text-indigo-800" title="Edit">
                            <i class="fas fa-edit w-5 h-5"></i>
                        </a>
                        <button type="button" class="text-red-600 hover:text-red-800" onclick="openDeleteModal('{{ $product->slug }}', '{{ $product->name }}')" title="Move to Trash">
                            <i class="fas fa-trash w-5 h-5"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="px-6 py-5 text-center text-gray-500">No products found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
