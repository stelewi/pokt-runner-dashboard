<?php

namespace App\Command;

use App\Service\NodeInfoRefresher;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RefreshNodeInfoCommand extends Command
{
    protected static $defaultName = 'app:refresh-node-info';
    protected static $defaultDescription = 'Get the latest NodeInfo for each node';

    /**
     * @var NodeInfoRefresher
     */
    private $refresher;

    /**
     * RefreshNodeInfoCommand constructor.
     * @param NodeInfoRefresher $refresher
     */
    public function __construct(NodeInfoRefresher $refresher)
    {
        parent::__construct();
        $this->refresher = $refresher;
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

        $io->write('Getting node info...');

        $nodesRefreshed = $this->refresher->refresh();

        $io->success("Refreshed info for {$nodesRefreshed} nodes!");

        return Command::SUCCESS;
    }
}
