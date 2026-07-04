/**
 * Tenant SaaS - shared front-end behaviour.
 *
 * Exposes a small `TenantApp` namespace used by individual page scripts
 * (see resources/views/auth/login.blade.php) so there is one place that
 * owns toast rendering, AJAX defaults, etc.
 */
const TenantApp = (function ($) {
    'use strict';

    // Attach the CSRF token to every AJAX request Laravel-style, so any
    // future AJAX-based forms (e.g. an AJAX login variant) work without
    // remembering to add the header manually each time.
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
    });

    function ensureToastContainer() {
        let $container = $('.toast-container');

        if ($container.length === 0) {
            $container = $('<div class="toast-container"></div>').appendTo('body');
        }

        return $container;
    }

    /**
     * @param {string} message
     * @param {'success'|'error'} type
     */
    function toast(message, type) {
        type = type === 'success' ? 'success' : 'error';

        const $toast = $('<div class="toast toast--' + type + '"></div>').text(message);

        ensureToastContainer().append($toast);

        window.setTimeout(function () {
            $toast.fadeOut(200, function () {
                $(this).remove();
            });
        }, 3500);
    }

    return { toast: toast };
})(jQuery);
