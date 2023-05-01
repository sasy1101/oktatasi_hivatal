<?php

namespace App\Command;

use App\Exception\ApplicationException;
use App\Services\ApplicationExampleData;
use App\Services\ApplicationManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:calculate-points',
    description: 'Add a short description for your command',
)]
class CalculatePointsCommand extends Command
{
    private ApplicationManager $applicationManager;

    public function __construct(string $name = null, ApplicationManager $applicationManager)
    {
        parent::__construct($name);
        $this->applicationManager = $applicationManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $input = ApplicationExampleData::EXAMPLE_DATA;

        foreach ($input as $person) {
            try {
                $result = $this->applicationManager->calculatePointsFromArray($person);
                $io->success((string) $result);
            } catch (ApplicationException $e) {
                $io->error($e->getMessage());
            }
        }

        $io->success('Process finished.');

        return Command::SUCCESS;
    }
}
