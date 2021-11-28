<?php

namespace App\Command;

use App\Service\NodeInfoService;
use App\Service\PocketClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TestPocketCommand extends Command
{
    protected static $defaultName = 'app:test-pocket';
    protected static $defaultDescription = 'Add a short description for your command';

    /**
     * @var PocketClient
     */
    private $client;

    /**
     * TestPocketCommand constructor.
     * @param PocketClient $client
     */
    public function __construct(PocketClient $client)
    {
        parent::__construct();
        $this->client = $client;
    }


    protected function configure(): void
    {
        $this
//            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
//            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $data = $this->client->nodeHeight();

        dump($data);


        return Command::SUCCESS;
    }
}
