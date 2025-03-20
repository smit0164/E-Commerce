$(document).ready(function () {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    const $categoryList = $('#category-list');
    let slugTimeout;

    $(document).on('click', '[data-action="open-modal"]', function () {
        const modalId = $(this).data('modal');
        const modal = $(`#${modalId}`);
        if (modalId === 'deleteCategoryModal') {
            const categoryId = $(this).data('category-id');
            const categoryName = $(this).data('category-name');
            openDeleteModal(categoryId, categoryName);
        } else if (modalId === 'editCategoryModal') {
            const categoryId = $(this).data('category-id');
            const categoryName = $(this).data('category-name');
            const categorySlug = $(this).data('category-slug');
            const categoryImage = $(this).data('category-image');
            $('#current-image-preview').empty();
            modal.find('.error').remove();
            modal.find('.border-red-500').removeClass('border-red-500');
            openEditModal(categoryId, categoryName, categorySlug, categoryImage);
        } else {
            $('#current-image-preview-add').empty();
            modal.find('.error').remove();
            modal.find('.border-red-500').removeClass('border-red-500');
            modal.removeClass('hidden');
        }
    });

    $(document).on('click', '[data-action="close-modal"]', function () {
        const modalId = $(this).data('modal');
        const modal = $(`#${modalId}`);

        // Reset form fields and validator
        const form = modal.find('form');
        if (form.length) {
            form[0].reset();
            const validator = form.validate();
            if (validator) {
                validator.resetForm();
            }
        }

        // Clear validation error messages and styling
        modal.find('.error').remove();
        modal.find('.border-red-500').removeClass('border-red-500');
        if (modalId === 'addCategoryModal') {
            $('#current-image-preview-add').empty();
        }
        // Hide modal without animation
        modal.addClass('hidden');
    });

    // Slug Generation
    $('#category-name').on('input', function () {
        clearTimeout(slugTimeout);
        const name = $(this).val();
        if (name.length >= 3) {
            slugTimeout = setTimeout(() => {
                $.ajax({
                    url: '/admin/categories/generate-slug',
                    method: 'POST',
                    data: { name, _token: csrfToken },
                    success: (response) => $('#category-slug').val(response.slug),
                    error: () => $('#category-slug').val('Error generating slug'),
                });
            }, 300);
        } else {
            $('#category-slug').val('');
        }
    });

    // Add Category Form Validation and Submission
    $('#addCategoryForm').validate({
        rules: {
            name: {
                required: true,
                minlength: 3,
                remote: {
                    url: '/admin/categories/check-unique',
                    type: 'POST',
                    data: {
                        name: () => $('#category-name').val(),
                        _token: csrfToken,
                    },
                    dataFilter: (response) => JSON.parse(response).isUnique,
                },
            },
            image: {
                required: true,
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
                required: 'Please upload an image',
                accept: 'Only image files (JPG, JPEG, PNG) are allowed',
            },
        },
        errorClass: 'text-red-500 text-sm mt-1',
        errorElement: 'div',
        highlight: (element) => $(element).addClass('border-red-500'),
        unhighlight: (element) => $(element).removeClass('border-red-500'),
        submitHandler: (form) => {
            const formData = new FormData(form);
            formData.append('_token', csrfToken);
            $.ajax({
                url: '/admin/categories/store',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: (response) => {
                    if (response.success) {
                        $('#addCategoryModal').addClass('hidden');
                        const categoryRow = createCategoryRow(response.category);
                        if ($categoryList.find('td[colspan="5"]').length) $categoryList.empty();
                        $categoryList.append(categoryRow);
                        form.reset();
                        $('#category-slug').val('');
                    } else {
                        alert(response.message);
                    }
                },
                error: (xhr) => {
                    alert('Error: ' + xhr.responseText);
                },
            });
        },
    });

    // Delete Category Form Submission
    $('#delete-category-form').on('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        $.ajax({
            url: '/admin/categories/delete',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: (response) => {
                if (response.success) {
                    const categoryId = formData.get('category_id');
                    $(`tr[data-category-id="${categoryId}"]`).remove();
                    const modal = $('#deleteCategoryModal');
                    modal.addClass('hidden');
                    if (!$categoryList.children().length) {
                        $categoryList.html(
                            '<tr><td colspan="5" class="px-6 py-5 text-center text-gray-500">No categories found.</td></tr>'
                        );
                    }
                } else {
                    alert(response.message);
                }
            },
            error: (xhr) => alert('Error deleting category: ' + xhr.statusText),
        });
    });

    // Helper Functions
    function createCategoryRow(response) {
        console.log(response);
        return `
            <tr class="hover:bg-gray-50 transition duration-200" data-category-id="${response.id}">
                <td class="px-6 py-5 font-medium">${response.id}</td>
                <td class="px-6 py-5">
                    <img src="${window.location.origin}/storage/categories/${response.image}" 
                     alt="${response.name}" 
                     class="max-w-[50px] h-auto">
                </td>
                <td class="px-6 py-5">${response.name}</td>
                <td class="px-6 py-5">${new Date().toLocaleDateString('en-US', {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric',
                })}</td>
                <td class="px-6 py-5 flex space-x-4">
                    <button  class="text-indigo-600 hover:text-indigo-800 transition" data-action="open-modal" data-modal="editCategoryModal" data-category-id="${response.id}" data-category-name="${response.name}" data-category-slug="${response.slug}" data-category-image=${response.image} title="Edit Category">
                        <i class="fas fa-edit w-5 h-5"></i>
                    </button>
                    <button class="text-red-600 hover:text-red-800 transition" data-action="open-modal" data-modal="deleteCategoryModal" data-category-id="${response.id}" data-category-name="${response.name}" title="Delete Category">
                        <i class="fas fa-trash w-5 h-5"></i>
                    </button>
                </td>
            </tr>
        `;
    }
    function openDeleteModal(categoryId, categoryName) {
        const modal = $('#deleteCategoryModal');
        $('#delete-category-name').text(categoryName);
        $('#delete-category-id').val(categoryId);
        modal.removeClass('hidden');
    }
    function openEditModal(categoryId, categoryName, categorySlug, categoryImage) {
        $('#edit-category-id').val(categoryId);
        $('#edit-category-name').val(categoryName);
        $('#edit-category-slug').val(categorySlug);
        if (categoryImage) {
            $('#current-image-preview').html(`<img src="/storage/categories/${categoryImage}" alt="${categoryName}" class="max-w-[100px] h-auto mb-2">`);
        } else {
            $('#current-image-preview').empty();
        }
        $('#editCategoryForm').append(`<input type="hidden" name="old_image" value="${categoryImage}">`);
        $('#editCategoryModal').removeClass('hidden');
    }
    // Update preview when a new image is selected
$('#edit-category-image').on('change', function () {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            $('#current-image-preview').html(`
                <img id="preview-image" src="${e.target.result}" 
                     alt="New Preview" class="max-w-[100px] h-auto mb-2">
            `);
        };
        reader.readAsDataURL(file);
    }
});
$('#category-image').on('change', function () {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            $('#current-image-preview-add').html(`
                <img id="preview-image" src="${e.target.result}" 
                     alt="New Preview" class="max-w-[100px] h-auto mb-2">
            `);
        };
        reader.readAsDataURL(file);
    }
});
    $('#edit-category-name').on('input', function () {
        clearTimeout(slugTimeout);
        const name = $(this).val();
        if (name.length >= 3) {
            slugTimeout = setTimeout(() => {
                $.ajax({
                    url: '/admin/categories/generate-slug',
                    method: 'POST',
                    data: { name, _token: csrfToken },
                    success: (response) => $('#edit-category-slug').val(response.slug),
                    error: () => $('#edit-category-slug').val('Error generating slug'),
                });
            }, 300);
        } else {
            $('#edit-category-slug').val('');
        }
    });
    $('#editCategoryForm').validate({
        rules: {
            name: {
                required: true,
                minlength: 3,
                remote: {
                    url: '/admin/categories/edit/check-unique', // Adjust as needed
                    type: 'POST',
                    data: {
                        name: () => $('#edit-category-name').val(),
                        category_id: () => $('#edit-category-id').val(), // Send ID to exclude the current category
                        _token: csrfToken,
                    },
                    dataFilter: (response) => JSON.parse(response).isUnique,
                },
            },
            image: {
                required: true,
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
                required:'Please upload an image',
                accept: 'Only image files (JPG, JPEG, PNG) are allowed',
            },
        },
        errorClass: 'text-red-500 text-sm mt-1',
        errorElement: 'div',
        highlight: (element) => $(element).addClass('border-red-500'),
        unhighlight: (element) => $(element).removeClass('border-red-500'),
        submitHandler: (form) => {
            const formData = new FormData(form);
            formData.append('_token', csrfToken);
           
            $.ajax({
                url: `/admin/categories/update`, // Adjust the update URL
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: (response) => {
                    if (response.success) {
                        $('#editCategoryModal').addClass('hidden');
                        const updatedRow = createCategoryRow(response.category);
                        $(`tr[data-category-id="${response.category.id}"]`).replaceWith(updatedRow);
                        form.reset();
                    } else {
                        alert(response.message);
                    }
                },
                error: (xhr) => {
                    alert('Error: ' + xhr.responseText);
                },
            });
        },
    });
    
});