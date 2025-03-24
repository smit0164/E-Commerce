@extends('layouts.admin.app')
@section('title', 'Edit Category - ' . $category->name)
@section('content')
    <div class="bg-white p-8 rounded-xl shadow-lg max-w-7xl mx-auto">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Edit Category: {{ $category->name }}</h2>

        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <form id="edit-category-form" action="{{ route('admin.categories.update', $category->slug) }}" method="POST"
            enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            <div>
                <label for="category-name" class="block text-gray-700 font-medium mb-2">Category Name</label>
                <input type="text" name="name" id="category-name" value="{{ old('name', $category->name) }}"
                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition @error('name') border-red-500 @enderror"
                    placeholder="Enter category name" required>
                @error('name')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="category-slug" class="block text-gray-700 font-medium mb-2">Slug</label>
                <input type="text" name="slug" id="category-slug" value="{{ old('slug', $category->slug) }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600"
                    placeholder="Slug will appear here" readonly>
                @error('slug')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="category-image" class="block text-gray-700 font-medium mb-2">Category Image</label>
                <div class="mb-4" id="current-image">
                    @if ($category->image)
                        <img src="{{ asset('storage/categories/' . $category->image) }}" alt="{{ $category->name }}"
                            class="max-w-[150px] h-auto rounded-md shadow-sm">
                    @else
                        <p class="text-gray-500">No current image</p>
                    @endif
                </div>
                <div id="image-preview" class="mb-2"></div>
                <input type="file" name="image" id="category-image"
                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition @error('image') border-red-500 @enderror"
                    accept="image/jpeg,image/png,image/jpg">
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
                        image: {
                            accept: 'image/jpeg,image/png,image/jpg',
                        },
                    },
                    messages: {
                        name: {
                            required: 'Please enter a category name',
                            minlength: 'Category name must be at least 3 characters long',
                            remote: 'This category name already exists',
                        },
                        image: {
                            accept: 'Only image files (JPG, JPEG, PNG) are allowed',
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
