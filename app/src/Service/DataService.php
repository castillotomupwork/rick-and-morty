<?php

namespace App\Service;

class DataService
{
    /**
     * Initializes the data service with the given data provider.
     *
     * @param DataProviderInterface $dataProvider
     */
    public function __construct(private DataProviderInterface $dataProvider) 
    {

    }

    /**
     * Retrieves a list of characters based on given parameters.
     *
     * @param array $params
     * @return array
     */
    public function characters(array $params = []): array
    {
        return $this->dataProvider->getCharacters($params);
    }

    /**
     * Retrieves a list of all dimensions.
     *
     * @return array
     */
    public function dimensions(): array
    {
        return $this->dataProvider->getDimensions();
    }

    /**
     * Retrieves a list of all locations.
     *
     * @return array
     */
    public function locations(): array
    {
        return $this->dataProvider->getLocations();
    }

    /**
     * Retrieves a list of all episodes.
     *
     * @return array
     */
    public function episodes(): array
    {
        return $this->dataProvider->getEpisodes();
    }
}