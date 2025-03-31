@extends('layouts.admin.app')

@section('title', 'Edit Static Block')

@section('content')
  
    <h1 class="text-2xl font-bold mb-4">Edit Static Block</h1>
    <form id="staticEditBlockForm" action="{{ route('admin.static_blocks.update', $staticBlock->slug) }}" method="POST" class="bg-white p-6 rounded shadow">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Title</label>
                <input type="text" name="title" id="title" class="w-full px-3 py-2 border rounded @error('title') border-red-500 @enderror" value="{{ old('title', $staticBlock->title) }}" required>
                @error('title')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Slug</label>
                <input type="text" name="slug" id="slug" class="w-full px-3 py-2 border rounded @error('slug') border-red-500 @enderror" value="{{ old('slug', $staticBlock->slug) }}" readonly required>
                @error('slug')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <!-- Content -->
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Content</label>
            <textarea name="content" id="summernote" class="w-full @error('content') border-red-500 @enderror" required>{{ old('content', $staticBlock->content) }}</textarea>
            @error('content')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Is Active -->
        <div class="mb-4">
            <label class="flex items-center">
                <span class="text-gray-700 text-sm font-bold mr-2">Status:</span>
                <div class="relative">
                    <select name="is_active" class="appearance-none border border-gray-300 rounded px-3 py-2 text-sm pr-8 bg-white">


                        <option value="active" {{ old('is_active', $staticBlock->is_active) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('is_active',$staticBlock->is_active) === 'inactive' ? 'selected' : '' }}>Inactive</option>

                    </select>
                </div>
            </label>
        </div>
        

        <!-- Submit Button -->
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update</button>
        <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"> 
            <a href="{{ route('admin.static_blocks.index') }}" >Cancel</a>
        </button>
        
    </form>

  
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

    <script>
        $(document).ready(function() {

            try {
                $('#summernote').summernote({
                    height: 300,
                    placeholder: 'Enter your content here...',
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture']],
                        ['view', ['fullscreen', 'codeview', 'help']]
                    ]
                });
                console.log('Summernote initialized successfully');
            } catch (e) {
                console.error('Summernote initialization failed:', e);
            }

            // AJAX for slug generation
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
            $('#staticEditBlockForm').validate({
                rules: {
                    title: {
                        required: true,
                        minlength: 3
                    },
                    content: {
                        required: true
                    }
                },
                messages: {
                    title: {
                        required: "Title is required.",
                        minlength: "Title must be at least 3 characters long."
                    },
                    content: {
                        required: "Content is required."
                    }
                },
                errorClass: "text-red-500 text-xs italic mt-1",
                errorElement: "p",
                highlight: function(element) {
                    $(element).addClass('border-red-500');
                },
                unhighlight: function(element) {
                    $(element).removeClass('border-red-500');
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>
@endsection