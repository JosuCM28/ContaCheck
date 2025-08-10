<!-- resources/views/components/toast.blade.php -->
<style>
    .toast-container {
        position: fixed;
        top: 1rem;
        right: 1rem;
        z-index: 9999;
    }

    .toast {
        --toast-color: #22c55e;
        /* default */
        display: flex;
        align-items: center;
        gap: 12px;
        background: #fff;
        color: #333;
        padding: 10px 1rem;
        border-radius: 10px;
        margin-bottom: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, .1);
        min-width: 280px;
        opacity: 0;
        transform: translateX(100%);
        transition: all .3s ease;
        border-left: 6px solid var(--toast-color);
    }

    .toast.show {
        opacity: 1;
        transform: translateX(0);
    }

    .toast[data-type="success"] {
        --toast-color: #22c55e;
    }

    /* verde */
    .toast[data-type="warning"] {
        --toast-color: #f59e0b;
    }

    /* amarillo */
    .toast[data-type="error"] {
        --toast-color: #ef4444;
    }

    /* rojo */

    .toast-badge {
        width: 30px;
        height: 30px;
        border-radius: 9999px;
        background: var(--toast-color);
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 30px;
    }

    .toast-badge svg {
        width: 20px;
        height: 20px;
        fill: #fff;
    }

    .toast-content {
        padding: 0.6rem 0;
        display: flex;
        gap: 4px;
        flex-direction: column;
    }

    .toast-title {
        font-weight: 700;
        margin-bottom: 2px;
        line-height: 1.1;
    }

    .toast-message {
        font-size: .95rem;
        line-height: 1.2;
    }
</style>

<div class="toast-container" id="toast-container" aria-live="polite" aria-atomic="true"></div>

<script>
    function svgFor(type) {
        if (type === 'success') {
            // check
            return `<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 16.2 4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4z"/></svg>`;
        }
        if (type === 'warning') {
            // exclamation
            return `
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <line x1="12" y1="7" x2="12" y2="14" stroke="white" stroke-width="2" stroke-linecap="round"/>
                    <circle cx="12" cy="17" r="1.5" fill="white"/>
                </svg>
            `;
        }
        // error: x
        return `
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <line x1="6" y1="6" x2="18" y2="18" stroke="white" stroke-width="2" stroke-linecap="round"/>
                <line x1="18" y1="6" x2="6" y2="18" stroke="white" stroke-width="2" stroke-linecap="round"/>
            </svg>
        `;

    }

    function showToast(message, type = 'success', title = '') {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = 'toast';
        toast.setAttribute('data-type', type);

        const defaultTitle = type.charAt(0).toUpperCase() + type.slice(1);
        toast.innerHTML = `
            <div class="toast-badge">${svgFor(type)}</div>
            <div class="toast-content">
            <div class="toast-title">${title || defaultTitle}</div>
            <div class="toast-message">${message}</div>
            </div>
        `;

        container.appendChild(toast);
        setTimeout(() => toast.classList.add('show'), 50);

        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3200);
    }

    // Autodisparo desde sesión Laravel
    @if (session('toast'))
        showToast(
            @json(session('toast')['message'] ?? 'Operación realizada'),
            @json(session('toast')['type'] ?? 'success'),
            @json(session('toast')['title'] ?? '')
        );
    @endif
</script>
