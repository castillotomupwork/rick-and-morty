import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = [
        'modal',
        'message',
        'dimension',
    ];

    static values = {
        endpoint: String,
    };

    open() {
        this.modalTarget.classList.add('flex');
        this.modalTarget.classList.remove('hidden');
    }

    close() {
        this.modalTarget.classList.add('hidden');
        this.modalTarget.classList.remove('flex');
    }

    applyFilters(event) {
        event.preventDefault();
        
        const formData = new FormData(event.target);
        const filters = Object.fromEntries(formData.entries());

        this.element.dispatchEvent(new CustomEvent('filters:changed', {
            detail: filters,
            bubbles: true
        }));

        this.close();
    }

    async loadDimensionOptions() {
        const url = this.endpointValue;

        try {
            const response = await fetch(url);
            const data = response.json();

            const dimensions = data.dimensions || [];

            this.dimensionTarget.innerHTML = '<option value="">All</option>';

            dimensions.forEach(dimension => {
                const option = document.createElement('option');
                option.value = dimension;
                option.textContent = dimension;
                this.dimensionTarget.appendChild(option);
            });

        } catch (error) {
            this.showMessage('Failed to load data.', 'error');

            console.error(error);
        }
    }

    showMessage(text = 'Action completed.', type = 'success', timeout = 10000) {
        const baseClasses = 'flex items-center gap-2 px-4 py-3 rounded mb-4 text-gray-800 transition-opacity duration-500';
        const types = {
            info: 'bg-blue-100',
            success: 'bg-green-100',
            error: 'bg-red-100',
            warning: 'bg-yellow-100',
        };
        
        this.messageTarget.className = `${baseClasses} ${types[type] || types.info}`;
        this.messageTarget.textContent = text;
        this.messageTarget.classList.remove('hidden', 'opacity-0');

        clearTimeout(this.messageTimeout);

        this.messageTimeout = setTimeout(() => {
            this.messageTarget.classList.add('opacity-0');

            setTimeout(() => {
                this.messageTarget.classList.add('hidden');
            }, 500);
        }, timeout);
    }
}
