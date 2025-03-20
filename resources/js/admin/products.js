$(document).ready(function () {
    loadProducts();
    let productData = {};
    const csrfToken = $('meta[name="csrf-token"]').attr("content");


    $(document).on("click", '[data-action="open-modal"]', function () {
        const modalId = $(this).data("modal");
        const modal = $(`#${modalId}`);
        if (modalId == "editProductModal") {
            const productId = $(this).data("product-id");
            const product = productData[productId];
             console.log(product);
             if (product) {
                $("#edit-product-id").val(product.id);
                $("#edit-product-name").val(product.name);
                $("#edit-product-slug").val(product.slug);
                $("#edit-product-description").val(product.description);
                $("#edit-product-price").val(product.price);
                $("#edit-product-quantity").val(product.quantity);
                if(product.status==1){
                    $("#edit-product-status").val('active');
                }else{
                    $("#edit-product-status").val('inactive');   
                }
                
                $("#edit-product-category").val(product.category_id);
                if(product.image){
                    const imageUrl = `${window.location.origin}/storage/products/${product.image}`;
                    $("#edit-product-image-preview-container").html(
                        `<img id="edit-product-image-preview" class="w-32 h-32 object-cover rounded-lg border border-gray-300" src="${imageUrl}">`
                    );
                }
        
                $("#editProductModal").removeClass("hidden"); // Open modal
            } else {
                alert("Product not found!");
            }
        }else if(modalId== 'deleteProductModal'){
                
        }else{
            modal.find(".error").remove();
            $("#current-product-image-preview-add").empty();
            modal.find(".border-red-500").removeClass("border-red-500");
            modal.removeClass("hidden");
        }
       
    });
    $(document).on("click", '[data-action="close-modal"]', function () {
        const modalId = $(this).data("modal");
        const modal = $(`#${modalId}`);

        const form = modal.closest("form");
        if (form.length) {
            form[0].reset();
            const validator = form.validate();
            if (validator) {
                validator.resetForm();
            }
        }
        modal.find(".error").remove();
        modal.find(".border-red-500").removeClass("border-red-500");
        modal.addClass("hidden");
    });

    //slug generate
    let slugTimeout;
    $("#product-name").on("input", function () {
        clearTimeout(slugTimeout);
        const name = $(this).val();
        if (name.length >= 3) {
            slugTimeout = setTimeout(() => {
                $.ajax({
                    url: "/admin/categories/generate-slug",
                    method: "POST",
                    data: { name, _token: csrfToken },
                    success: (response) =>
                        $("#product-slug").val(response.slug),
                    error: () =>
                        $("#product-slug").val("Error generating slug"),
                });
            }, 300);
        } else {
            $("#product-slug").val("");
        }
    });
    $("#product-image").on("change", function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $("#current-product-image-preview-add").html(`
                <img id="preview-image" src="${e.target.result}" 
                     alt="New Preview" class="max-w-[100px] h-auto mb-2">
            `);
            };
            reader.readAsDataURL(file);
        }
    });

    $("#addProductsForm").validate({
        rules: {
            name: {
                required: true,
                minlength: 3,
                remote: {
                    url: "/admin/products/check-unique",
                    type: "POST",
                    data: {
                        name: () => $("#product-name").val(),
                        _token: csrfToken,
                    },
                    dataFilter: (response) => JSON.parse(response).isUnique,
                },
            },
            description: { required: true, minlength: 5 },
            price: { required: true, number: true, min: 0 },
            quantity: { required: true, digits: true, min: 1 },
            category_id: { required: true },
            status: { required: true },
            image: { required: true, accept: "image/jpeg,image/png,image/jpg" },
        },
        messages: {
            name: {
                required: "Product name is required",
                minlength: "Name must be at least 3 characters",
                remote: "This name already exists",
            },
            description: {
                required: "Description is required",
                minlength: "Description must be at least 5 characters",
            },
            price: {
                required: "Price is required",
                number: "Price must be a number",
                min: "Price cannot be negative",
            },
            quantity: {
                required: "Quantity is required",
                digits: "Quantity must be a whole number",
                min: "Quantity must be at least 1",
            },
            category_id: { required: "Select a category" },
            status: { required: "Status is required" },
            image: {
                required: "Image is required",
                accept: "Only JPG, JPEG, or PNG allowed",
            },
        },
        errorClass: "text-red-500 text-sm mt-1",
        errorElement: "div",
        errorPlacement: function (error, element) {
            error.insertAfter(element);
        },

        highlight: function (element) {
            $(element).addClass("border-red-500");
        },
        unhighlight: function (element) {
            $(element).removeClass("border-red-500");
        },
        submitHandler: (form) => {
            const formData = new FormData(form);
            formData.append("_token", csrfToken);
            $.ajax({
                url: "/admin/products/store",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: (response) => {
                    if (response.success) {
                        $("#addProductsModal").addClass("hidden");
                        form.reset();
                        $("#product-slug").val("");
                        loadProducts();
                    } else {
                        alert(response.message);
                    }
                },
                error: (xhr) =>
                    alert(
                        "Error: " + xhr.responseJSON?.message || "Unknown error"
                    ),
            });
        },
    });
    function loadProducts() {
        $.ajax({
            url: "/admin/products/load",
            method: "GET",
            success: (response) => {
                if (response.success) {
                    const productList = $("#product-list");
                    productList.empty(); // Clear previous data

                    if (response.success && response.products.length > 0) {
                        response.products.forEach((product) => {
                            productData[product.id] = product;
                            productList.append(createProductRow(product));
                        });
                    } else {
                        // Show "No products found" message
                        productList.append(`
                        <tr>
                            <td colspan="8" class="px-6 py-5 text-center text-gray-500">
                                No products found.
                            </td>
                        </tr>
                    `);
                    }
                } else {
                    alert("Failed to load products");
                }
            },
            error: (xhr) => {
                alert("Error: " + xhr.responseJSON?.message || "Unknown error");
            },
        });
    }
    function createProductRow(product) {
        return `
        <tr class="hover:bg-gray-50 transition duration-200" data-product-id="${
            product.id
        }">
            <td class="px-6 py-5 font-medium">${product.id}</td>
            <td class="px-6 py-5">
                <img src="${
                    window.location.origin
                }/storage/products/${product.image}" 
                     alt="${product.name}" class="max-w-[50px] h-auto">
            </td>
            <td class="px-6 py-5">${product.name}</td>
            <td class="px-6 py-5">₹${parseFloat(product.price).toFixed(2)}</td>
            <td class="px-6 py-5">${product.quantity}</td>
            <td class="px-6 py-5">
                <span class="px-3 py-1 rounded-full text-xs font-semibold 
                    ${
                        product.status === 1
                            ? "bg-green-200 text-green-800"
                            : "bg-red-200 text-red-800"
                    }">
                    ${product.status === 1 ? "Active" : "Inactive"}
                </span>
            </td>
            <td class="px-6 py-5">${new Date(
                product.created_at
            ).toLocaleDateString("en-US", {
                month: "short",
                day: "2-digit",
                year: "numeric",
            })}</td>
            <td class="px-6 py-5 flex space-x-4">
                <button data-action="open-modal" data-modal="editProductModal"
                        data-product-id="${product.id}"
                        class="text-indigo-600 hover:text-indigo-800 transition edit-product"
                        title="Edit Product">
                    <i class="fas fa-edit w-5 h-5"></i>
                </button>
                <button data-action="open-modal" data-modal="deleteProductModal"
                        data-product-id="${product.id}"
                        class="text-red-600 hover:text-red-800 transition delete-product"
                        title="Delete Product">
                    <i class="fas fa-trash w-5 h-5"></i>
                </button>
            </td>
        </tr>
    `;
    }
});
