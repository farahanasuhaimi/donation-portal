import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const toast = document.getElementById('flash-toast');
    if (toast) {
        const closeButton = toast.querySelector('[data-toast-close]');
        const dismiss = () => {
            toast.classList.add('opacity-0', 'translate-y-2');
            window.setTimeout(() => toast.remove(), 200);
        };

        if (closeButton) {
            closeButton.addEventListener('click', dismiss);
        }

        const timeout = Number(toast.dataset.timeout || 4000);
        if (Number.isFinite(timeout) && timeout > 0) {
            window.setTimeout(dismiss, timeout);
        }
    }

    // Copy/share logic is handled inline to avoid asset build issues.
});
