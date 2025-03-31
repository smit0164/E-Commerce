@extends('layouts.admin.app')
@section('title', 'Edit Category - ' . $category->name)
@section('content')
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-2xl mx-auto">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Edit Category: {{ $category->name }}</h2>


        <form id="edit-category-form" action="{{ route('admin.categories.update', $category->slug) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div>
                <label for="category-name" class="block text-sm text-gray-700 font-medium mb-2">Category Name</label>
                <input type="text" name="name" id="category-name" value="{{ old('name', $category->name) }}"
                class="w-full px-3 py-2 border  rounded-md bg-gray-50 
                hover:border-indigo-400 focus:border-indigo-500 focus:bg-white focus:shadow-md focus:outline-none 
                transition-all duration-200 ease-in-out placeholder-gray-400 @error('name') border-red-500 @enderror" 
                    placeholder="Enter category name" required>
                @error('name')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="category-slug" class="block text-gray-700 font-medium mb-2">Slug</label>
                <input type="text" name="slug" id="category-slug" value="{{ old('slug', $category->slug) }}"
                class="w-full px-3 py-2 border  rounded-md bg-gray-50 
                hover:border-indigo-400 focus:border-indigo-500 focus:bg-white focus:shadow-md focus:outline-none 
                transition-all duration-200 ease-in-out placeholder-gray-400 @error('name') border-red-500 @enderror" 
                    placeholder="Slug will appear here" readonly>
                @error('slug')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label for="category-status" class="block text-gray-700 font-medium mb-2">Status</label>
                <select name="status" id="category-status"
                class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 
                hover:border-indigo-400 focus:border-indigo-500 focus:bg-white focus:shadow-md focus:outline-none 
                transition-all duration-200 ease-in-out @error('status') border-red-500 @enderror">
                    <option value="active" {{ old('status', $category->status) === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $category->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label for="category-image" class="block text-gray-700 font-medium mb-2">Category Image</label>
                
                
                <input type="file" name="image" id="category-image"
                class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 
                hover:border-indigo-400 focus:border-indigo-500 focus:bg-white focus:shadow-md focus:outline-none 
                transition-all duration-200 ease-in-out" 
                 accept="image/jpeg,image/png,image/jpg">
                    <div id="image-preview" class="mt-2"></div>
                    <div class="mb-4" id="current-image">
                        @if ($category->image)
                            <img src="{{ $category->getCategoryImageUrl() }}"  alt="{{ $category->name }}"
                                class="max-w-[150px] h-auto rounded-md shadow-sm">
                        @else
                            <p class="text-gray-500">No current image</p>
                        @endif
                    </div>
                @error('image')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.categories.index') }}"
                    class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 transition">
                    Cancel
                </a>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">
                    Update
                </button>
            </div>
        </form>

        <!-- Inline JavaScript -->
        <script>
            $(document).ready(function() {
                const csrfToken = $('meta[name="csrf-token"]').attr('content');
                let slugTimeout;

                // Slug Generation
                $('#category-name').on('input', function() {
                    clearTimeout(slugTimeout);
                    const name = $(this).val();
                    if (name.length >= 3) {
                        slugTimeout = setTimeout(() => {
                            $.ajax({
                                url: '{{ route('admin.generate-slug') }}',
                                method: 'POST',
                                data: {
                                    name,
                                    _token: csrfToken
                                },
                                success: (response) => $('#category-slug').val(response.slug),
                                error: () => $('#category-slug').val('Error generating slug'),
                            });
                        }, 300);
                    } else {
                        $('#category-slug').val('');
                    }
                });

                // Image Preview with Clear on Change
                $('#category-image').on('change', function() {
                    const file = this.files[0];
                    $('#image-preview').empty(); // Clear any existing preview
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            $('#image-preview').html(`
                            <img src="${e.target.result}" 
                                 alt="Preview" 
                                 class="max-w-[150px] h-auto rounded-md shadow-sm mb-2">
                        `);
                            $('#current-image').addClass('hidden'); // Hide the current image
                        };
                        reader.readAsDataURL(file);
                    } else {
                        $('#current-image').removeClass('hidden'); // Show the current image if no new file
                    }
                });

                // Form Validation
                $('#edit-category-form').validate({
                    rules: {
                        name: {
                            required: true,
                            minlength: 3,
                            remote: {
                                url: '{{ route('admin.categories.edit.check-unique')}}',
                                type: 'POST',
                                data: {
                                    name: () => $('#category-name').val(),
                                    category_id: {{ $category->id }},
                                    _token: csrfToken,
                                },
                                dataFilter: (response) => JSON.parse(response).isUnique,
                            },
                        },
                       
                    },
                    messages: {
                        name: {
                            required: 'Please enter a category name',
                            minlength: 'Category name must be at least 3 characters long',
                            remote: 'This category name already exists',
                        },
                    },
                    errorClass: 'text-red-500 text-sm mt-1',
                    errorElement: 'div',
                    highlight: (element) => $(element).addClass('border-red-500'),
                    unhighlight: (element) => $(element).removeClass('border-red-500'),
                    submitHandler: function(form) {
                        form.submit();
                    }
                });
            });
        </script>
    </div>
@endsection
