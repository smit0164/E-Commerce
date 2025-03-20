
$(document).ready(function () {
    $("#adminLoginForm").validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 6
            }
        },
        messages: {
            email: {
                required: "Email is required",
                email: "Enter a valid email address"
            },
            password: {
                required: "Password is required",
                minlength: "Password must be at least 6 characters"
            }
        },
        errorClass: "text-red-500 text-sm mt-1",
        errorElement: "span",
        highlight: function (element) {
            $(element).addClass("border-red-500");
        },
        unhighlight: function (element) {
            $(element).removeClass("border-red-500");
        }
    });
});

