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

    const copyButtons = document.querySelectorAll('[data-copy-target],[data-copy-value]');
    if (!copyButtons.length) return;

    copyButtons.forEach((button) => {
        button.addEventListener('click', async () => {
            const directValue = button.getAttribute('data-copy-value');
            const selector = button.getAttribute('data-copy-target');
            const input = selector ? document.querySelector(selector) : null;
            const value = directValue || input?.value;
            if (!value) return;

            const showCopied = () => {
                const label = button.getAttribute('data-copy-label') || 'Copied';
                const original = button.textContent;
                button.textContent = label;
                button.disabled = true;
                window.setTimeout(() => {
                    button.textContent = original;
                    button.disabled = false;
                }, 1500);
            };

            try {
                await navigator.clipboard.writeText(value);
                showCopied();
            } catch (error) {
                if (input) {
                    input.select();
                }

                const fallback = document.createElement('textarea');
                fallback.value = value;
                fallback.setAttribute('readonly', '');
                fallback.style.position = 'absolute';
                fallback.style.left = '-9999px';
                document.body.appendChild(fallback);
                fallback.select();
                const copied = document.execCommand('copy');
                fallback.remove();
                if (copied) {
                    showCopied();
                }
            }
        });
    });
});
