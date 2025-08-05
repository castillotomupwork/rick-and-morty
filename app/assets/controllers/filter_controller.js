import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = [
        'modal',
        'message',
        'dimension',
        'location',
        'episode',
        'container',
        'button',
    ];

    static values = {
        dimensionsEndpoint: String,
        locationsEndpoint: String,
        episodesEndpoint: String,
    };

    connect() {
        this.loadDimensionOptions();
        this.loadLocationsOptions();
        this.loadEpisodesOptions();

        this.adjustLayout();
        window.addEventListener('resize', this.adjustLayout.bind(this));
    }

    disconnect() {
        window.removeEventListener('resize', this.adjustLayout.bind(this));
    }

    overlayClose(event) {
        if (event.target === this.modalTarget) {
            this.close();
        }
    }

    open() {
        this.modalTarget.classList.add('flex');
        this.modalTarget.classList.remove('hidden');
        this.modalTarget.classList.remove('opacity-0');
    }

    close() {
        this.modalTarget.classList.add('opacity-0');
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
        const url = this.dimensionsEndpointValue;

        try {
            const response = await fetch(url);
            const data = await response.json();

            const dimensions = data || [];

            this.dimensionTarget.innerHTML = '<option value="">All</option>';

            dimensions.forEach(dimension => {
                const option = document.createElement('option');
                option.value = dimension.id;
                option.textContent = dimension.name;
                this.dimensionTarget.appendChild(option);
            });

        } catch (error) {
            this.showMessage('Failed to load dimensions.', 'error');

            console.error(error);
        }
    }

    async loadLocationsOptions() {
        const url = this.locationsEndpointValue;

        try {
            const response = await fetch(url);
            const data = await response.json();

            const locations = data || [];

            this.locationTarget.innerHTML = '<option value="">All</option>';

            locations.forEach(location => {
                const option = document.createElement('option');
                option.value = location.id;
                option.textContent = location.name;
                this.locationTarget.appendChild(option);
            });

        } catch (error) {
            this.showMessage('Failed to load locations.', 'error');

            console.error(error);
        }
    }

    async loadEpisodesOptions() {
        const url = this.episodesEndpointValue;

        try {
            const response = await fetch(url);
            const data = await response.json();

            const episodes = data || [];

            this.episodeTarget.innerHTML = '<option value="">All</option>';

            episodes.forEach(episode => {
                const option = document.createElement('option');
                option.value = episode.id;
                option.textContent = episode.name;
                this.episodeTarget.appendChild(option);
            });

        } catch (error) {
            this.showMessage('Failed to load episodes.', 'error');

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

    adjustLayout() {
        const isSmallHeight = window.innerHeight <= 620;

        if (!isSmallHeight) {
            this.resetLayout();
            return;
        }

        this.containerTarget.classList.add('grid', 'grid-cols-2');

        const children = Array.from(this.containerTarget.children).filter(
            (child) => child.getAttribute('data-filter-target') !== 'button'
        );

        children.forEach((child, index) => {
            child.classList.remove('pr-1', 'pl-1');

            if (index % 2 === 0) {
                child.classList.add('pr-1');
            } else {
                child.classList.add('pl-1');
            }
        });

        this.buttonTarget.classList.add('col-span-2');
    }

    resetLayout() {
        this.containerTarget.classList.remove('grid', 'grid-cols-2');

        const children = Array.from(this.containerTarget.children);

        children.forEach(child => child.classList.remove('pr-1', 'pl-1'));

        this.buttonTarget.classList.remove('col-span-2');
    }
}
