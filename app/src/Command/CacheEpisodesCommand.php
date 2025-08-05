<?php

namespace App\Command;

use App\Service\ApiDataProvider;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:cache:episodes',
    description: 'Generate Rick and Morty Episodes to cache',
)]
class CacheEpisodesCommand extends Command
{
    /**
     * Initializes the API data provider for episode caching.
     *
     * @param ApiDataProvider $apiDataProvider
     */
    public function __construct(private ApiDataProvider $apiDataProvider)
    {
        parent::__construct();
    }

    /**
     * Executes the episode caching process.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return integer
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->info('Starting episode cache generation...');

        $episodes = $this->apiDataProvider->getEpisodes();

        if (count($episodes) > 0) {
            $io->success('Episode cache generated successfully.');
        } else {
            $io->warning('No episodes were cached.');
        }

        return Command::SUCCESS;
    }
}
