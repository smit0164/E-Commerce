@extends('layouts.admin.app')

@section('title', 'Add New Product')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-md max-w-4xl mx-auto mt-6">
    <h2 class="text-2xl font-semibold text-gray-900 mb-6">Add New Product</h2>

    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form id="product-form" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-4">
                <!-- Name & Slug -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="product-name" class="block text-sm font-medium text-gray-700">Product Name</label>
                        <input type="text" 
                               name="name" 
                               id="product-name" 
                               value="{{ old('name') }}"
                               class="w-full px-3 py-2 border rounded-md bg-gray-50 
                                      hover:border-indigo-400 focus:border-indigo-500 focus:bg-white focus:shadow-md focus:outline-none 
                                      transition-all duration-200 ease-in-out placeholder-gray-400 @error('name') border-red-500 @enderror" 
                               placeholder="Enter product name">
                        @error('name')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="product-slug" class="block text-sm font-medium text-gray-700">Slug</label>
                        <input type="text" 
                               name="slug" 
                               id="product-slug" 
                               value="{{ old('slug') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 
                                      hover:border-indigo-400 focus:border-indigo-500 focus:bg-white focus:shadow-md focus:outline-none 
                                      transition-all duration-200 ease-in-out placeholder-gray-400" 
                               placeholder="Slug will appear here"
                               readonly>
                        @error('slug')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Price & Quantity -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="product-price" class="block text-sm font-medium text-gray-700">Price</label>
                        <input type="number" 
                               name="price" 
                               id="product-price" 
                               step="0.01" 
                               min="0"
                               value="{{ old('price') }}"
                               class="w-full px-3 py-2 border rounded-md bg-gray-50 
                                      hover:border-indigo-400 focus:border-indigo-500 focus:bg-white focus:shadow-md focus:outline-none 
                                      transition-all duration-200 ease-in-out placeholder-gray-400 @error('price') border-red-500 @enderror" 
                               placeholder="Enter product price">
                        @error('price')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="product-quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                        <input type="number" 
                               name="quantity" 
                               id="product-quantity" 
                               step="1" 
                               min="0"
                               value="{{ old('quantity') }}"
                               class="w-full px-3 py-2 border rounded-md bg-gray-50 
                                      hover:border-indigo-400 focus:border-indigo-500 focus:bg-white focus:shadow-md focus:outline-none 
                                      transition-all duration-200 ease-in-out placeholder-gray-400 @error('quantity') border-red-500 @enderror" 
                               placeholder="Enter quantity">
                        @error('quantity')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label for="product-status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" 
                            id="product-status"
                            class="w-full px-3 py-2 border rounded-md bg-gray-50 
                                   hover:border-indigo-400 focus:border-indigo-500 focus:bg-white focus:shadow-md focus:outline-none 
                                   transition-all duration-200 ease-in-out @error('status') border-red-500 @enderror">
                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', 'active') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                

                <!-- Description -->
                <div>
                    <label for="product-description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" 
                              id="product-description"
                              class="w-full px-3 py-2 border rounded-md bg-gray-50 
                                     hover:border-indigo-400 focus:border-indigo-500 focus:bg-white focus:shadow-md focus:outline-none 
                                     transition-all duration-200 ease-in-out placeholder-gray-400 @error('description') border-red-500 @enderror" 
                              rows="3" 
                              placeholder="Enter product description...">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-4">
                <!-- Categories -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Categories</label>
                    <div id="category-box" class="max-h-32 overflow-y-auto border border-gray-300 rounded-md p-2 bg-gray-50 @error('category_id') border-red-500 @enderror">
                        @foreach ($categories as $category)
                            <label class="flex items-center space-x-2 mb-1">
                                <input type="checkbox" 
                                       name="category_id[]" 
                                       value="{{ $category->id }}"
                                       {{ is_array(old('category_id', [])) && in_array($category->id, old('category_id', [])) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-gray-700 text-sm">{{ $category->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('category_id')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Image Upload -->
                <div>
                    <label for="product-image" class="block text-sm font-medium text-gray-700">Product Image</label>
                    <input type="file" 
                           name="image" 
                           id="product-image" 
                           class="w-full px-3 py-2 border rounded-md bg-gray-50 
                                  hover:border-indigo-400 focus:border-indigo-500 focus:bg-white focus:shadow-md focus:outline-none 
                                  transition-all duration-200 ease-in-out @error('image') border-red-500 @enderror" 
                           accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                    <div id="image-preview" class="mt-2 flex flex-wrap gap-2"></div>
                    @error('image')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-2 mt-6">
            <a href="{{ route('admin.products.index') }}" 
               class="bg-gray-200 text-gray-700 px-5 py-2 rounded-lg hover:bg-gray-300 transition duration-200 text-sm shadow-sm hover:shadow-md">
                Cancel
            </a>
            <button type="submit" 
                    class="bg-indigo-600 text-white px-5 py-2 rounded-lg hover:bg-indigo-700 transition duration-200 text-sm shadow-sm hover:shadow-md">
                Save
            </button>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    let slugTimeout;

    // Image Preview
    $('#product-image').on('change', function(e) {
        const previewContainer = $('#image-preview');
        previewContainer.empty();

        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const img = $('<img>', {
                    src: event.target.result,
                    class: 'w-24 h-24 object-cover rounded-lg border border-gray-300 shadow-sm'
                });
                previewContainer.append(img);
            };
            reader.readAsDataURL(file);
        }
    });

    // Auto-generate slug
    $('#product-name').on('input', function() {
        const name = $(this).val();

        if (name.length >= 3) {
            clearTimeout(slugTimeout);
            slugTimeout = setTimeout(() => {
                $.ajax({
                    url: '{{ route('admin.generate-slug') }}',
                    method: 'POST',
                    data: { name, _token: csrfToken },
                    success: function(response) {
                        $('#product-slug').val(response.slug);
                    },
                    error: function() {
                        $('#product-slug').val('Error generating slug');
                    }
                });
            }, 300);
        } else {
            $('#product-slug').val('');
        }
    });

    // jQuery Validation
    $('#product-form').validate({
        rules: {
            name: {
                required: true,
                minlength: 3,
                remote: {
                    url: '{{ route('admin.products.check-unique') }}',
                    type: 'POST',
                    data: {
                        name: function() {
                            return $('#product-name').val();
                        },
                        _token: csrfToken,
                    },
                    dataFilter: function(response) {
                        return JSON.parse(response).isUnique ? 'true' : 'false';
                    }
                }
            },
            price: {
                required: true,
                number: true,
                min: 0
            },
            quantity: {
                required: true,
                digits: true,
                min: 0
            },
            status: {
                required: true
            },
            'category_id[]': {
                required: true,
                minlength: 1
            },
            description: {
                required: true,
                minlength: 5
            },
            image: {
                required: true,
            }
        },
        messages: {
            name: {
                required: 'Product name is required',
                minlength: 'Name must be at least 3 characters',
                remote: 'This name already exists'
            },
            price: {
                required: 'Price is required',
                number: 'Price must be a number',
                min: 'Price cannot be negative'
            },
            quantity: {
                required: 'Quantity is required',
                digits: 'Quantity must be a whole number',
                min: 'Quantity cannot be negative'
            },
            status: {
                required: 'Status is required'
            },
            'category_id[]': {
                required: 'Please select at least one category',
                minlength: 'Please select at least one category'
            },
            description: {
                required: 'Description is required',
                minlength: 'Description must be at least 5 characters'
            },
            image: {
                required: 'Image is required',
            }
        },
        errorClass: 'text-red-500 text-xs mt-1',
        errorElement: 'div',
        errorPlacement: function(error, element) {
            if (element.attr('name') === 'category_id[]') {
                error.insertAfter('#category-box');
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function(element) {
            if ($(element).attr('name') === 'category_id[]') {
                $('#category-box').addClass('border-red-500').removeClass('border-gray-300');
            } else {
                $(element).addClass('border-red-500').removeClass('border-gray-300');
            }
        },
        unhighlight: function(element) {
            if ($(element).attr('name') === 'category_id[]') {
                $('#category-box').removeClass('border-red-500').addClass('border-gray-300');
            } else {
                $(element).removeClass('border-red-500').addClass('border-gray-300');
            }
        },
        submitHandler: function(form) {
            form.submit(); // Standard form submission
        }
    });
});
</script>
@endsection