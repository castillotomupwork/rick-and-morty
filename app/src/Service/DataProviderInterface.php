<?php

namespace App\Service;

interface DataProviderInterface
{
    /**
     * Returns a filtered list of characters based on provided parameters.
     *
     * @param array $params
     * @return array
     */
    public function getCharacters(array $params = []): array;

    /**
     * Returns a list of all available dimensions.
     *
     * @return array
     */
    public function getDimensions(): array;

    /**
     * Returns a list of all available locations.
     *
     * @return array
     */
    public function getLocations(): array;
    
    /**
     * Returns a list of all available episodes.
     *
     * @return array
     */
    public function getEpisodes(): array;
}