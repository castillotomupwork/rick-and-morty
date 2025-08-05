<?php

namespace App\Command;

use App\Service\ApiDataProvider;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:cache:characters',
    description: 'Generate Rick and Morty Characters to cache',
)]
class CacheCharactersCommand extends Command
{
    /**
     * Initializes the API data provider for character caching.
     *
     * @param ApiDataProvider $apiDataProvider
     */
    public function __construct(private ApiDataProvider $apiDataProvider)
    {
        parent::__construct();
    }

    /**
     * Executes the character caching process.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return integer
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->info('Starting character cache generation...');

        $characters = $this->apiDataProvider->getCacheCharacters();

        if (count($characters) > 0) {
            $io->success('Character cache generated successfully.');
        } else {
            $io->warning('No characters were cached.');
        }

        return Command::SUCCESS;
    }
}
