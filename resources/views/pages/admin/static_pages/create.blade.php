@extends('layouts.admin.app')

@section('title', 'Create Static Page')

@section('content')
<div class="bg-white p-8 rounded-xl shadow-lg max-w-7xl">
    <h1 class="text-2xl font-bold mb-4">Create New Static Page</h1>

    <form action="{{ route('admin.static_pages.store') }}" method="POST" id="staticPageForm">
        @csrf
        <div class="grid grid-cols-1 gap-4">
            <!-- Title and Slug side by side -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 text-sm font-medium">Title</label>
                    <input type="text" name="title" id="title" class="w-full px-3 py-2 border rounded @error('title') border-red-500 @enderror" value="{{ old('title') }}" placeholder="Enter your title">
                    @error('title')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold">Slug</label>
                    <input type="text" name="slug" id="slug" class="w-full px-3 py-2 border rounded @error('slug') border-red-500 @enderror" value="{{ old('slug') }}" placeholder="Slug will appear" readonly>
                    @error('slug')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-white text-gray-700 focus:ring-indigo-500 focus:border-indigo-500 hover:border-indigo-400 transition duration-150 ease-in-out py-2 px-3 @error('status') border-red-500 @enderror">
                    <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Content full width -->
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Content</label>
                <textarea name="content" id="summernote" class="w-full @error('content') border-red-500 @enderror" required>{{ old('content') }}</textarea>
                @error('content')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2 rounded">Save</button>
            <a href="{{ route('admin.static_pages.index') }}" class="bg-gray-200 text-gray-700 px-5 py-2 rounded-lg hover:bg-gray-300 transition duration-200 text-sm shadow-sm hover:shadow-md">Cancel</a>
        </div>
    </form>
</div>

<!-- Include jQuery, Summernote, and jQuery Validation -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>

<script>
    $(document).ready(function() {

        // Initialize Summernote
        $('#summernote').summernote({
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        // Auto-generate slug from title
        $('#title').on('input', function() {
            var title = $(this).val();
            if (title) {
                $.ajax({
                    url: '{{ route("admin.static_blocks.generate_slug") }}',
                    method: 'POST',
                    data: {
                        title: title,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log(response.slug);
                        $('#slug').val(response.slug);
                    },
                    error: function(xhr) {
                        console.log('Slug generation error:', xhr);
                        alert('Slug generation failed!');
                    }
                });
            } else {
                $('#slug').val('');
            }
        });

        // jQuery Validation
        $("#staticPageForm").validate({
            rules: {
                title: {
                    required: true,
                    maxlength: 255
                },
                status: {
                    required: true,
                },
            },
            messages: {
                title: {
                    required: "Please enter a title",
                    maxlength: "Title cannot exceed 255 characters."
                },
                status: {
                    required: "Please select a status",
                },
            },
            errorElement: "span",
            errorClass: "text-red-500 text-xs mt-1",
            highlight: function(element) {
                $(element).addClass("border-red-500");
            },
            unhighlight: function(element) {
                $(element).removeClass("border-red-500");
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
</script>
@endsection