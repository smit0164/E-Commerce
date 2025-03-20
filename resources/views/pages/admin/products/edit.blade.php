@extends('layouts.admin.app')

@section('title', 'Edit Product')

@section('content')

    <div class="bg-white p-6 rounded-xl shadow-md max-w-4xl mx-auto mt-6">
        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Edit Product</h2>

        <form id="edit-product-form" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            <input type="hidden" id="product-id" name="product_id" value="{{ $product->id }}" />


            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <!-- Name & Slug -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="product-name" class="block text-gray-700 font-medium mb-1 text-sm">Product
                                Name</label>
                            <input type="text" name="name" id="product-name" value="{{ old('name', $product->name) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-gray-50">
                            @error('name')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label for="product-slug" class="block text-gray-700 font-medium mb-1 text-sm">Slug</label>
                            <input type="text" name="slug" id="product-slug" value="{{ old('slug', $product->slug) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-gray-50" readonly>
                            @error('slug')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Price & Quantity -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="product-price" class="block text-gray-700 font-medium mb-1 text-sm">Price</label>
                            <input type="number" name="price" id="product-price" step="0.01" min="0"
                                value="{{ old('price', $product->price) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-gray-50">
                            @error('price')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label for="product-quantity"
                                class="block text-gray-700 font-medium mb-1 text-sm">Quantity</label>
                            <input type="number" name="quantity" id="product-quantity"step="1" min="0"
                                value="{{ old('quantity', $product->quantity) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-gray-50">
                            @error('quantity')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="product-status" class="block text-gray-700 font-medium mb-1 text-sm">Status</label>
                        <select name="status" id="product-status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-gray-50">
                            <option value="1" {{ old('status', $product->status) == 1 ? 'selected' : '' }}>Active
                            </option>
                            <option value="0" {{ old('status', $product->status) == 0 ? 'selected' : '' }}>Inactive
                            </option>
                        </select>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="product-description"
                            class="block text-gray-700 font-medium mb-1 text-sm">Description</label>
                        <textarea name="description" id="product-description"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-gray-50">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <!-- Categories -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-1 text-sm">Categories</label>
                        <div class="max-h-32 overflow-y-auto border border-gray-300 rounded-lg p-2 bg-gray-50"
                            id="category-box">
                            @foreach ($categories as $category)
                                <label class="flex items-center space-x-2 mb-1">
                                    <input type="checkbox" name="category_id[]" value="{{ $category->id }}"
                                        @if (in_array($category->id, old('category_id', $selectedCategories))) checked @endif
                                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span class="text-gray-700 text-sm">{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('category_id')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Image -->
                    <div>
                        <label for="product-image" class="block text-gray-700 font-medium mb-1 text-sm">Product
                            Image</label>
                        <input type="file" name="image" id="product-image"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-gray-50"
                            accept="image/jpeg,image/png,image/jpg">

                        <input type="hidden" name="old_image" value="{{ $product->image }}">

                        <div id="image-preview" class="mt-3">
                            <img src="{{ asset('storage/products/' . $product->image) }}" alt="Product Image"
                                class="w-24 h-24 object-cover rounded-lg border border-gray-300 shadow-sm">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ route('admin.products') }}" class="bg-gray-200 text-gray-700 px-5 py-2 rounded-lg">Cancel</a>
                <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded-lg">Update</button>
            </div>
        </form>
    </div>


    <script>
        let slugTimeout;

        // Image Preview on Change
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

        // jQuery Validation
        $(document).ready(function() {
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            $('#product-name').on('input', function() {
                const name = $(this).val();

                if (name.length >= 3) {
                    clearTimeout(slugTimeout);
                    slugTimeout = setTimeout(() => {
                        $.ajax({
                            url: '/admin/categories/generate-slug',
                            method: 'POST',
                            data: {
                                name,
                                _token: csrfToken
                            },
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

            $('#edit-product-form').validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 3,
                        remote: {
                            url: '/admin/products/check-edit-unique',
                            type: 'POST',
                            data: {
                                name: function() {
                                    return $('#product-name').val();
                                },
                                product_id: function() {
                                    return $('#product-id').val();
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
                        extension: "jpg|jpeg|png"
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
                    category_id: {
                        required: 'Please select at least one category'
                    },
                    description: {
                        required: 'Description is required',
                        minlength: 'Description must be at least 5 characters'
                    },
                    image: {
                        extension: 'Only JPG, JPEG, or PNG files are allowed'
                    }
                },
                errorClass: 'text-red-500 text-xs mt-1',
                errorElement: 'div',
                errorPlacement: function(error, element) {
                    if (element.attr('name') === 'category_id[]') {
                        error.insertAfter($('#category-box'));
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
                        // Check if at least one checkbox is checked
                        if ($('input[name="category_id[]"]:checked').length > 0) {
                            $('#category-box').removeClass('border-red-500').addClass(
                            'border-gray-300');
                        }
                    } else {
                        $(element).removeClass('border-red-500').addClass('border-gray-300');
                    }
                },

                submitHandler: function(form) {
                    const formData = new FormData(form);
                    const $submitButton = $(form).find('button[type="submit"]');
                    $submitButton.prop('disabled', true).text('Saving...');
                    $.ajax({
                        url: '/admin/products/update',
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success) {
                                window.location.href = '{{ route('admin.products') }}';
                            } else {
                                alert(response.message);
                                $submitButton.prop('disabled', false).text('Save');
                            }
                        },
                        error: function(xhr) {
                            alert('Error: ' + (xhr.responseJSON?.message ||
                                'Unknown error'));
                            $submitButton.prop('disabled', false).text('Save');
                        }
                    });
                },
 

            });

        });
    </script>

@endsection
