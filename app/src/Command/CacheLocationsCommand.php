<?php

namespace App\Command;

use App\Service\ApiDataProvider;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:cache:locations',
    description: 'Generate Rick and Morty Locations to cache',
)]
class CacheLocationsCommand extends Command
{
    /**
     * Initializes the API data provider for location caching.
     *
     * @param ApiDataProvider $apiDataProvider
     */
    public function __construct(private ApiDataProvider $apiDataProvider)
    {
        parent::__construct();
    }

    /**
     * Executes the location caching process.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return integer
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->info('Starting location cache generation...');

        $locations = $this->apiDataProvider->getLocations();

        if (count($locations) > 0) {
            $io->success('Location cache generated successfully.');
        } else {
            $io->warning('No locations were cached.');
        }

        return Command::SUCCESS;
    }
}
