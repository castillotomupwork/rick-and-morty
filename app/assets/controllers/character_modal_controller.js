import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = [
        'modal', 
        'image', 
        'name', 
        'species', 
        'gender', 
        'status',
        'type',
        'origin',
        'dimension',
        'location',
        'episode'
    ];

    overlayClose(event) {
        if (event.target === this.modalTarget) {
            this.close();
        }
    }

    open(event) {
        const character = event.currentTarget;

        this.imageTarget.src = character.dataset.characterModalImage;
        this.nameTarget.textContent = character.dataset.characterModalName;
        this.speciesTarget.textContent = character.dataset.characterModalSpecies;
        this.genderTarget.textContent = character.dataset.characterModalGender;
        this.statusTarget.textContent = character.dataset.characterModalStatus;
        this.typeTarget.textContent = character.dataset.characterModalType;
        this.originTarget.textContent = character.dataset.characterModalOrigin;
        this.dimensionTarget.textContent = character.dataset.characterModalDimension;
        this.locationTarget.textContent = character.dataset.characterModalLocation;
        this.episodeTarget.textContent = character.dataset.characterModalEpisode;

        this.modalTarget.classList.add('flex');
        this.modalTarget.classList.remove('hidden');
        this.modalTarget.classList.remove('opacity-0');
    }

    close() {
        this.modalTarget.classList.add('opacity-0');
        this.modalTarget.classList.add('hidden');
        this.modalTarget.classList.remove('flex');
    }
}
