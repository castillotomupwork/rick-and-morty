<?php

namespace App\Service;

class DataProviderFactory
{
    /**
     * Initializes the DataProviderFactory with available data provider implementations.
     *
     * @param ApiDataProvider $apiProvider
     * @param DatabaseDataProvider $dbProvider
     */
    public function __construct(
        private ApiDataProvider $apiProvider,
        private DatabaseDataProvider $dbProvider
    ) {
    }

    /**
     * Returns the appropriate data provider based on the given type.
     *
     * @param string $type
     * @return DataProviderInterface
     */
    public function select(string $type): DataProviderInterface
    {
        return $type === 'db' ? $this->dbProvider : $this->apiProvider;
    }
}
