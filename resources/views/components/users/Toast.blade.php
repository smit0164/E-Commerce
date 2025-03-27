<div id="toast-container" class="fixed bottom-6 right-6 space-y-4 z-50"></div>

<script>
    function showToast(message, type = 'normal', duration = 4000) {
        let borderColor, textColor, bgGradient, icon, ringColor, showCloseButton = true;

        // Define styles and icons based on type
        switch (type) {
            case 'success':
                borderColor = 'border-teal-500';
                textColor = 'text-teal-100';
                bgGradient = 'bg-gradient-to-br from-teal-700/90 to-teal-900/90';
                ringColor = 'ring-teal-500/50';
                icon = `
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                `;
                break;
            case 'error':
                borderColor = 'border-red-500';
                textColor = 'text-red-100';
                bgGradient = 'bg-gradient-to-br from-red-700/90 to-red-900/90';
                ringColor = 'ring-red-500/50';
                icon = `
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                `;
                showCloseButton = false; // No close button for error
                break;
            case 'warning':
                borderColor = 'border-yellow-500';
                textColor = 'text-yellow-900';
                bgGradient = 'bg-gradient-to-br from-yellow-400/90 to-yellow-600/90';
                ringColor = 'ring-yellow-500/50';
                icon = `
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                `;
                break;
            default: // normal
                borderColor = 'border-blue-500';
                textColor = 'text-blue-100';
                bgGradient = 'bg-gradient-to-br from-blue-700/90 to-blue-900/90';
                ringColor = 'ring-blue-500/50';
                icon = `
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                `;
        }

        // Create toast element
        let toast = document.createElement('div');
        toast.innerHTML = `
            <div class="relative max-w-md ${bgGradient} ${borderColor} border-l-4 rounded-2xl p-4 flex items-center space-x-4 shadow-xl ${ringColor} ring-1 ring-opacity-50 backdrop-blur-sm transition-all duration-500 ease-in-out transform translate-x-full opacity-0">
                <div class="${textColor} flex-shrink-0">${icon}</div>
                <p class="text-sm ${textColor} font-medium tracking-wide flex-grow">${message}</p>
                ${
                    showCloseButton ? `
                    <button class="${textColor} opacity-70 hover:opacity-100 focus:outline-none transition-all duration-300 transform hover:scale-110 hover:rotate-180" onclick="this.parentElement.parentElement.classList.add('translate-x-full', 'opacity-0'); setTimeout(() => this.parentElement.parentElement.remove(), 500);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    ` : ''
                }
            </div>
        `;

        const container = document.getElementById('toast-container');
        container.appendChild(toast);

        // Animation in
        setTimeout(() => {
            toast.firstElementChild.classList.remove('translate-x-full', 'opacity-0');
            toast.firstElementChild.classList.add('translate-x-0', 'opacity-100');
        }, 50);

        // Animation out (auto-dismiss)
        if (!showCloseButton || type !== 'error') {
            setTimeout(() => {
                toast.firstElementChild.classList.remove('translate-x-0', 'opacity-100');
                toast.firstElementChild.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => toast.remove(), 500);
            }, duration);
        }
    }

    // Expose showToast globally
    window.showToast = showToast;

    // Example usage (for testing)
    // showToast("Operation completed successfully!", "success");
    // showToast("Something went wrong!", "error");
    // showToast("Heads up, check this out!", "warning");
    // showToast("Just a heads up!", "normal");
</script>

<style>
    /* Optional: Add custom animation keyframes for extra polish */
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
</style>