/**
 * Booking link handler for Cinetixx component
 *
 * @package     Weltspiegel\Component\Cinetixx
 * @copyright   Weltspiegel Cottbus
 * @license     MIT
 */

(function() {
    'use strict';

    /**
     * Create and show modal with booking iframe
     */
    function openBookingModal(showId) {
        const bookingUrl = `https://www.kinoheld.de/kino-cottbus/filmtheater-weltspiegel/vorstellung/${showId}?mode=widget#panel-seats`;

        // Create modal backdrop
        const backdrop = document.createElement('div');
        backdrop.className = 'booking-modal-backdrop';
        backdrop.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 9998;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        `;

        // Create modal container
        const modal = document.createElement('div');
        modal.className = 'booking-modal';
        modal.style.cssText = `
            position: relative;
            width: 100%;
            max-width: 1200px;
            height: 90vh;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        `;

        // Create close button
        const closeBtn = document.createElement('button');
        closeBtn.innerHTML = '&times;';
        closeBtn.className = 'booking-modal-close';
        closeBtn.setAttribute('aria-label', 'Schließen');
        closeBtn.style.cssText = `
            position: absolute;
            top: 10px;
            right: 10px;
            width: 40px;
            height: 40px;
            border: none;
            background: rgba(255, 255, 255, 0.9);
            color: #333;
            font-size: 30px;
            line-height: 1;
            cursor: pointer;
            border-radius: 50%;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
        `;

        // Create iframe
        const iframe = document.createElement('iframe');
        iframe.src = bookingUrl;
        iframe.className = 'booking-modal-iframe';
        iframe.style.cssText = `
            width: 100%;
            height: 100%;
            border: none;
        `;
        iframe.setAttribute('allowfullscreen', '');

        // Assemble modal
        modal.appendChild(closeBtn);
        modal.appendChild(iframe);
        backdrop.appendChild(modal);

        // Close handlers
        const closeModal = () => {
            backdrop.remove();
            document.body.style.overflow = '';
        };

        closeBtn.addEventListener('click', closeModal);
        backdrop.addEventListener('click', (e) => {
            if (e.target === backdrop) {
                closeModal();
            }
        });

        // Handle escape key
        const handleEscape = (e) => {
            if (e.key === 'Escape') {
                closeModal();
                document.removeEventListener('keydown', handleEscape);
            }
        };
        document.addEventListener('keydown', handleEscape);

        // Prevent body scroll
        document.body.style.overflow = 'hidden';

        // Add to DOM
        document.body.appendChild(backdrop);

        // Focus close button for accessibility
        closeBtn.focus();
    }

    /**
     * Initialize booking link handlers
     */
    function initBookingLinks() {
        const bookingLinks = document.querySelectorAll('.booking-link');

        bookingLinks.forEach(link => {
            const bookingType = link.dataset.bookingType;
            const showId = link.dataset.showId;

            if (bookingType === 'modal' && showId) {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    openBookingModal(showId);
                });
            }
        });
    }

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initBookingLinks);
    } else {
        initBookingLinks();
    }

})();
