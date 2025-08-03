import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = [
        'message',
        'characters', 
        'pageLabel',
        'prevButton',
        'nextButton',
        'firstButton',
        'lastButton',
    ];

    static values = {
        endpoint: String,
        page: { type: Number, default: 1 },
        pages: {
            prev: { type: Number, default: 0 },
            next: { type: Number, default: 0 },
            first: { type: Number, default: 0 },
            last: { type: Number, default: 0 },
        },
    };

    connect() {
        this.pageValue = 1;

        this.filters = {};

        this.loadPage();

        this.element.addEventListener('filters:changed', this.updateFilters.bind(this));
    }

    async loadPage() {
        const params = new URLSearchParams({
                    page: this.pageValue,
                    ...this.filters
                });

        const url = `${this.endpointValue}?${params}`;

        try {
            const response = await fetch(url);
            const data = response.json();

            if (!data?.characters) {
                if (data?.error) {
                    this.showMessage(data.error, 'error');
                } else {
                    this.showMessage('System failed.', 'error');
                }

                this.charactersTarget.innerHTML = '';

            } else if (data.characters.length === 0) {
                this.charactersTarget.innerHTML = `
                    <div class="text-gray-500 bg-white p-4 rounded-xl shadow-md text-center col-span-1 sm:col-span-2 md:col-span-3 lg:col-span-4">
                        No characters found.
                    </div>
                `;
            } else {
                this.charactersTarget.innerHTML = data.characters.map(character => `
                    <article class="character bg-white p-4 rounded-xl shadow-md text-center">
                        <img
                            src="${character.image}"
                            alt="${character.name}"
                            class="mx-auto rounded-full w-24 h-24 mb-3"
                        />
                        <h2 class="text-lg font-semibold">${character.name}</h2>
                        <div class="grid grid-cols-2 gap-y-1 text-sm text-gray-600">
                            <div class="font-medium text-right pr-2">Species:</div>
                            <div class="text-left">${character.species}</div>
                            <div class="font-medium text-right pr-2">Gender:</div>
                            <div class="text-left">${character.gender}</div>
                            <div class="font-medium text-right pr-2">Status:</div>
                            <div class="text-left">${character.status}</div>
                        </div>
                    </article>
                `).join("");

                this.showMessage('Characters loaded successfully.');                
            }

            this.pageLabelTarget.textContent = data?.pagination?.pageLabel ?? '1';

            this.prevButtonTarget.disabled = data?.pagination?.disablePrev;

            this.nextButtonTarget.disabled = data?.pagination?.disableNext;

            this.firstButtonTarget.disabled = data?.pagination?.disableFirst;

            this.lastButtonTarget.disabled = data?.pagination?.disableLast;

            this.pagesValue = {
                prev: data?.pagination?.prev,
                next: data?.pagination?.next,
                first: data?.pagination?.first,
                last: data?.pagination?.last,
            };
        } catch (error) {
            this.showMessage('Failed to load data.', 'error');

            console.error(error);
        }
    }

    paginate(event) {
        const type = event.currentTarget.dataset.type;
        

        if (!type) {
            this.showMessage('Something went wrong. Unable to complete the action.', 'error');

            console.error("Missing param: 'type'");

            return;
        }

        const button = this[`${type}ButtonTarget`];

        if (!button) {
            this.showMessage('Oops! We couldn\'t find the button you were trying to use.', 'error');

            console.error(`Button target "${type}ButtonTarget" not found`);

            return;
        }

        if (!button.disabled) {
            this.pageValue = this.pagesValue[type];

            this.loadPage();
        }
    }

    updateFilters(event) {
        this.pageValue = 1;

        this.filters = event.detail;

        this.loadPage();
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
