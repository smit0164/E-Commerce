<div id="toast-container" class="fixed bottom-5 right-5 space-y-4 z-50"></div>

<script>
    function showToast(message, type = 'normal', duration = 3000) {
        let borderColor, textColor, icon;

        // Define styles based on type
        switch (type) {
            case 'success':
                borderColor = 'border-teal-600';
                textColor = 'text-white';
                icon = `
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                `;
                break;
            case 'error':
                borderColor = 'border-red-600';
                textColor = 'text-white';
                icon = `
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                `;
                break;
            case 'warning':
                borderColor = 'border-yellow-600';
                textColor = 'text-black';
                icon = `
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                `;
                break;
            default:
                borderColor = 'border-blue-600';
                textColor = 'text-white';
                icon = `
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                `;
        }

        let toast = document.createElement('div');
        toast.innerHTML = `
            <div class="max-w-sm bg-gray-800 ${borderColor} border-l-4 shadow-xl rounded-lg p-4 flex items-center space-x-4 transition-all duration-500 transform translate-x-100">
                <div class="${textColor} flex-shrink-0">${icon}</div>
                <p class="text-sm ${textColor} font-medium flex-grow">${message}</p>
                <button class="text-gray-400 hover:text-gray-200 focus:outline-none transition-transform duration-200 transform hover:scale-110 hover:rotate-90" onclick="this.parentElement.parentElement.remove()">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;

        const container = document.getElementById('toast-container');
        toast.classList.add('opacity-999');
        container.appendChild(toast);

        // Animation in
        setTimeout(() => {
            toast.firstElementChild.classList.remove('translate-x-100');
            toast.firstElementChild.classList.add('translate-x-0');
        }, 50);

        // Animation out
        setTimeout(() => {
            toast.firstElementChild.classList.remove('translate-x-0');
            toast.firstElementChild.classList.add('translate-x-100');
            setTimeout(() => toast.remove(), 500);
        }, duration);
    }

    window.showToast = showToast;
</script>
