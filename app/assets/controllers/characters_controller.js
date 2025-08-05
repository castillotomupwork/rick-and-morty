import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = [
        'loading',
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
        this.showLoading();

        const params = new URLSearchParams({
                    page: this.pageValue,
                    ...this.filters
                });

        const url = `${this.endpointValue}?${params}`;

        try {
            const response = await fetch(url);
            const data = await response.json();
            const characters = data?.characters ?? [];

            if (characters.length === 0) {
                if (data?.error) {
                    this.showMessage(data.error, 'error');

                    this.charactersTarget.innerHTML = '';
                } else {
                    this.charactersTarget.innerHTML = `
                        <div class="text-gray-500 bg-white p-4 rounded-xl shadow-md text-center col-span-1 sm:col-span-2 md:col-span-3 lg:col-span-4 xl:col-span-5">
                            No characters found.
                        </div>
                    `;
                }
            } else {
                this.charactersTarget.innerHTML = characters.map(character => `
                    <article 
                        class="character bg-white p-4 rounded-xl shadow-md text-center cursor-pointer hover:shadow-lg transition"
                        data-action="click->character-modal#open"
                        data-character-modal-image="${character.image}"
                        data-character-modal-name="${character.name}"
                        data-character-modal-species="${character.species}"
                        data-character-modal-gender="${character.gender}"
                        data-character-modal-status="${character.status}"
                        data-character-modal-type="${character.type}"
                        data-character-modal-origin="${character.origin}"
                        data-character-modal-dimension="${character.dimension}"
                        data-character-modal-location="${character.location}"
                        data-character-modal-episode="${character.episode}"
                    >
                        <img
                            src="${character.image}"
                            alt="${character.name}"
                            class="mx-auto rounded-full w-30 h-30 mb-3"
                        />
                        <h2 class="text-lg font-semibold">${character.name}</h2>
                    </article>
                `).join("");
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
        } finally {
            this.hideLoading();
        }
    }

    showLoading() {
        this.loadingTarget.classList.remove('hidden', 'opacity-0', 'pointer-events-none');
        this.loadingTarget.classList.add('opacity-30');
    }

    hideLoading() {
        this.loadingTarget.classList.remove('opacity-30');
        this.loadingTarget.classList.add('hidden', 'opacity-0', 'pointer-events-none');
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
        const baseClasses = 'flex items-center gap-2 px-4 py-3 rounded mb-4 text-gray-800 opacity-0 transition-fade';
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
