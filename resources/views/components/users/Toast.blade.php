<div id="toast-container" class="fixed top-5 right-5 space-y-3 z-50"></div>

<script>

        function showToast(message, type = 'normal', duration = 3000) {
            let bgColor, borderColor, textColor, icon;

            // Define styles based on type
            switch (type) {
                case 'success':
                    bgColor = 'bg-teal-100';
                    borderColor = 'border-teal-500';
                    textColor = 'text-teal-600';
                    icon = '<i class="fa-solid fa-check-circle"></i>';
                    break;
                case 'error':
                    bgColor = 'bg-red-100';
                    borderColor = 'border-red-500';
                    textColor = 'text-red-600';
                    icon = '<i class="fa-solid fa-times-circle"></i>';
                    break;
                case 'warning':
                    bgColor = 'bg-yellow-100';
                    borderColor = 'border-yellow-500';
                    textColor = 'text-yellow-600';
                    icon = '<i class="fa-solid fa-exclamation-circle"></i>';
                    break;
                default:
                    bgColor = 'bg-blue-100';
                    borderColor = 'border-blue-500';
                    textColor = 'text-blue-600';
                    icon = '<i class="fa-solid fa-info-circle"></i>';
            }

            let toast = document.createElement('div');
            toast.innerHTML = `
                <div class="max-w-xs ${bgColor} ${borderColor} border-l-4 shadow-lg rounded-md p-4 flex items-center space-x-10 opacity-999 translate-x-100 transition-all duration-500">
                    <div class="${textColor} text-xl">${icon}</div>
                    <p class="text-sm text-gray-700">${message}</p>
                </div>
            `;

            document.getElementById('toast-container').appendChild(toast);
            setTimeout(() => toast.classList.remove('opacity-0', 'translate-x-10'), 50);

            // Remove toast after duration
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-x-10');
                setTimeout(() => toast.remove(), 500);
            }, duration);
        }

        // ✅ Make function globally accessible
        window.showToast = showToast;
    
</script>
