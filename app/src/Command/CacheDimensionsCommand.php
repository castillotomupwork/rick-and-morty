<?php

namespace App\Command;

use App\Service\ApiDataProvider;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:cache:dimensions',
    description: 'Generate Rick and Morty Dimensions to cache',
)]
class CacheDimensionsCommand extends Command
{
    /**
     * Initializes the API data provider for dimension caching.
     *
     * @param ApiDataProvider $apiDataProvider
     */
    public function __construct(private ApiDataProvider $apiDataProvider)
    {
        parent::__construct();
    }

    /**
     * Executes the dimension caching process.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return integer
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->info('Starting dimension cache generation...');

        $dimensions = $this->apiDataProvider->getDimensions();

        if (count($dimensions) > 0) {
            $io->success('Dimensions cache generated successfully.');
        } else {
            $io->warning('No characters were cached.');
        }

        return Command::SUCCESS;
    }
}
