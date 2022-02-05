<?php

namespace App\Command;

use App\Service\NodeInfoService;
use App\Service\PocketClient;
use App\Service\PoktValidatorService;
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
     * @var PoktValidatorService
     */
    private $poktValidatorService;

    /**
     * TestPocketCommand constructor.
     * @param PocketClient $client
     * @param PoktValidatorService $poktValidatorService
     */
    public function __construct(PocketClient $client, PoktValidatorService $poktValidatorService)
    {
        parent::__construct();
        $this->client = $client;
        $this->poktValidatorService = $poktValidatorService;
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

        $validator = $this->poktValidatorService
            ->getPoktValidator('CA52AFB99444C02878E1E1D5AB7D557E67D67D81');

        $this->poktValidatorService->refreshRewards($validator);



//        $data = $this->client->nodeHeight();
//
//        dump($data);
//
//        $data = $this->client->queryAccountTxs(
//            'CA52AFB99444C02878E1E1D5AB7D557E67D67D81',
//        );
//
//        file_put_contents(__DIR__ . '/../../stuff.json', json_encode($data, JSON_PRETTY_PRINT));


        return Command::SUCCESS;
    }
}
