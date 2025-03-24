@extends('layouts.admin.app')

@section('title', 'Add Category')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md max-w-2xl mx-auto">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">Add Category</h2>

    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form id="categoryForm" action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="category-name" class="block text-sm font-medium text-gray-700">Category Name</label>
            <input type="text" 
                   name="name" 
                   id="category-name" 
                   value="{{ old('name') }}"
                   class="w-full px-3 py-2 border  rounded-md bg-gray-50 
                          hover:border-indigo-400 focus:border-indigo-500 focus:bg-white focus:shadow-md focus:outline-none 
                          transition-all duration-200 ease-in-out placeholder-gray-400 @error('name') border-red-500 @enderror" 
                   placeholder="Enter category name">
            @error('name')
                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="category-slug" class="block text-sm font-medium text-gray-700">Slug</label>
            <input type="text" 
                   name="slug" 
                   id="category-slug" 
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

        <div class="mb-3">
            <label for="category-image" class="block text-sm font-medium text-gray-700">Category Image</label>
            <input type="file" 
                   name="image" 
                   id="category-image" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 
                          hover:border-indigo-400 focus:border-indigo-500 focus:bg-white focus:shadow-md focus:outline-none 
                          transition-all duration-200 ease-in-out" 
                   accept="image/jpeg,image/png,image/jpg">
            <div id="image-preview" class="mt-2 flex flex-wrap gap-2"></div>
            @error('image')
                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex justify-end space-x-2">
            <a href="{{ route('admin.categories.index') }}" 
               class="bg-gray-200 text-gray-700 px-3 py-1 rounded hover:bg-gray-300 text-sm">
                Cancel
            </a>
            <button type="submit" 
                    class="bg-indigo-600 text-white px-3 py-1 rounded hover:bg-indigo-700 text-sm">
                Save
            </button>
        </div>
    </form>
</div>

<script>
$(document).ready(function () {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    let slugTimeout;

    // Image Preview
    $("#category-image").on("change", function (e) {
        const previewContainer = $("#image-preview");
        previewContainer.empty();

        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (event) {
                const img = $('<img>', {
                    src: event.target.result,
                    class: 'w-20 h-20 rounded-md object-cover border border-gray-300 shadow-sm'
                });
                previewContainer.append(img);
            };
            reader.readAsDataURL(file);
        }
    });

    // Auto-generate slug
    $("#category-name").on("input", function () {
        const name = $(this).val();

        if (name.length >= 3) {
            clearTimeout(slugTimeout);
            slugTimeout = setTimeout(() => {
                $.ajax({
                    url: '{{ route('admin.generate-slug')}}',
                    method: 'POST',
                    data: { name, _token: csrfToken },
                    success: function (response) {
                        $("#category-slug").val(response.slug);
                    },
                    error: function () {
                        $("#category-slug").val('Error generating slug');
                    }
                });
            }, 300);
        } else {
            $("#category-slug").val('');
        }
    });

    // jQuery Validation
    $("#categoryForm").validate({
        rules: {
            name: {
                required: true,
                minlength: 2,
                remote: {
                    url: '{{ route('admin.categories.check-unique') }}',
                    type: 'POST',
                    data: {
                        name: function () {
                            return $("#category-name").val();
                        },
                        _token: csrfToken,
                    },
                    dataFilter: function (response) {
                        return JSON.parse(response).isUnique ? 'true' : 'false';
                    }
                }
            },
            image: {
                required: true,
                accept: "image/jpeg,image/png,image/jpg"
            }
        },
        messages: {
            name: {
                required: "Category name is required",
                minlength: "Category name must be at least 2 characters",
                remote: "This category name already exists"
            },
            image: {
                required: "Please upload an image",
                accept: "Only JPG, JPEG, or PNG files are allowed"
            }
        },
        errorClass: "text-red-500 text-xs mt-1",
        errorElement: "span",
        highlight: function (element) {
            $(element).addClass('border-red-500').removeClass('border-gray-300');
        },
        unhighlight: function (element) {
            $(element).removeClass('border-red-500').addClass('border-gray-300');
        },
        submitHandler: function (form) {
             form.submit();
        }
    });
});
</script>
@endsection