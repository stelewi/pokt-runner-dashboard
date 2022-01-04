<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class LoadTestEthCommand extends Command
{
    protected static $defaultName = 'app:load-test-eth';
    protected static $defaultDescription = 'Add a short description for your command';

    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * HarmonyClient constructor.
     * @param HttpClientInterface $httpClient
     */
    public function __construct(HttpClientInterface $httpClient)
    {
        parent::__construct();
        $this->httpClient = $httpClient;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('url', InputArgument::OPTIONAL, 'url to test')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $url = $input->getArgument('url');

        $requestsMade = 0;
        $starttime = microtime(true);

        while ($requestsMade < 200)
        {
            $io->info("requests made $requestsMade");

//            $requestData = '{"relay_network_id":"0021","payload":{"data":"{\"jsonrpc\":\"2.0\",\"method\":\"eth_getBalance\",\"params\":[\"0xe7a24E61b2ec77d3663ec785d1110688d2A32ecc\", \"latest\"],\"id\":1}","method":"POST","path":"","headers":{}}}';
                           //'{"relay-network-id":"0021","payload":"{\"jsonrpc\":\"2.0\",\"method\":\"eth_getBalance\",\"params\":[\"0xda9dfa130df4de4673b89022ee50ff26f6ea73cf\",5067433],\"id\":1}","method":"POST","path":"","headers":{}}'
            $requestData = '{"relay_network_id":"0021","payload":{"data":"{\"jsonrpc\":\"2.0\",\"method\":\"eth_blockNumber\",\"params\":[],\"id\":1}","method":"POST","path":"","headers":{}}}';


            $response = $this->httpClient->request('POST', $url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'max_duration' => 5,
                'body' => $requestData
            ]);

            $io->info($response->getContent());
            $requestsMade++;
        }

        $endtime = microtime(true);

        $totalTime = $endtime - $starttime;

        $io->info("$requestsMade requests made in $totalTime ms");

        return Command::SUCCESS;
    }
}
