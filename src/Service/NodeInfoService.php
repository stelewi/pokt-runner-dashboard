<?php


namespace App\Service;


use App\Entity\Node;
use App\Entity\NodeInfo;
use App\Repository\NodeInfoRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class NodeInfoService
{
    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * @var HarmonyClient
     */
    private $harmonyClient;

    /**
     * @var PocketClient
     */
    private $pocketClient;

    /**
     * @var EthClient
     */
    private $ethClient;

    /**
     * @var NodeInfoRepository
     */
    private $nodeInfoRepo;

    /**
     * NodeInfoService constructor.
     * @param HttpClientInterface $httpClient
     * @param HarmonyClient $harmonyClient
     * @param PocketClient $pocketClient
     * @param EthClient $ethClient
     * @param NodeInfoRepository $nodeInfoRepo
     */
    public function __construct(HttpClientInterface $httpClient, HarmonyClient $harmonyClient, PocketClient $pocketClient, EthClient $ethClient, NodeInfoRepository $nodeInfoRepo)
    {
        $this->httpClient = $httpClient;
        $this->harmonyClient = $harmonyClient;
        $this->pocketClient = $pocketClient;
        $this->ethClient = $ethClient;
        $this->nodeInfoRepo = $nodeInfoRepo;
    }


    public function getNodeInfo(Node $node, $createNew = true): NodeInfo
    {
        if(!$createNew)
        {
            $nodeInfo = $this->nodeInfoRepo->findOneBy(
                ['node' => $node],
                ['time' => 'DESC']
            );

            if($nodeInfo !== null)
            {
                return $nodeInfo;
            }
        }

        switch ($node->getType())
        {
            case Node::TYPE_POCKET:
                return $this->getPoktNodeInfo($node);

            case Node::TYPE_HARMONY:
                return $this->getHarmonyNodeInfo($node);

            case Node::TYPE_ETH_MAIN:
                return $this->getEthNodeInfo($node);

            default:
                throw new \Exception('Unknown Node Type');
        }
    }

    private function getEthNodeInfo(Node $node): NodeInfo
    {
        $time = new \DateTimeImmutable();
        $isSynced = null;
        $height = null;
        $blockChainHeight = null;
        $info = null;

        /******** Check our node height ********/
        $data = $this->ethClient->eth_syncing('http://' . $node->getPrivateIp() . ':8545');

        $isSynced = !$data;
        $blockChainHeight = isset($data['highestBlock']) ? (int) hexdec($data['highestBlock']) : null;
        $height = isset($data['currentBlock']) ? (int) hexdec($data['currentBlock']) : null;

        $nodeInfo = new NodeInfo(
            $time,
            $isSynced,
            $height,
            $blockChainHeight,
            $node
        );

        $nodeInfo->setInfo($info);

        return $nodeInfo;
    }

    private function getPoktNodeInfo(Node $node): NodeInfo
    {
        $time = new \DateTimeImmutable();
        $isSynced = null;
        $height = null;
        $blockChainHeight = null;
        $jailed = null;
        $tokens = null;:win32_query_service_status()
        $isStaked = null;
        $validatorAddress = null;
        $votingPower = null;

        $poktRpcPort = $node->getRpcPort() ?? '8082';
        $tendermintRpcPort = $node->getSecondaryRpcPort() ?? '26657';

        /******** Check blockchain height ********/
        $blockChainHeight = $this->pocketClient->nodeHeight();


        /******** Check our node  ********/
        $data = $this->pocketClient->nodeStatus('http://' . $node->getPrivateIp() . ":{$tendermintRpcPort}/");

        if($data !== null)
        {
            $isSynced = !$data['sync_info']['catching_up'];
            $height = $data['sync_info']['latest_block_height'];
            $validatorAddress = $data['validator_info']['address'];
            $votingPower = $data['validator_info']['voting_power'];
        }

        /******** Check Validator  ********/
        if($validatorAddress !== null)
        {
            $data = $this->pocketClient->queryValidatorNode(
                'http://' . $node->getPrivateIp() . ':{$poktRpcPort}/',
                $validatorAddress
            );

            if($data !== null)
            {
                $jailed = $data['jailed'];
                $tokens = $data['tokens'];
            }
        }

        return new NodeInfo(
            $time,
            $isSynced,
            $height,
            $blockChainHeight,
            $node,
            $jailed,
            null,
            $tokens,
            $isStaked,
            $validatorAddress,
            $votingPower
        );
    }

    private function getHarmonyNodeInfo(Node $node): NodeInfo
    {
        $time = new \DateTimeImmutable();
        $isSynced = null;
        $height = null;
        $blockChainHeight = null;

        /******** Check isSynced? ********/
        $response = $this->httpClient->request(
            'GET',
            'http://' . $node->getHostname() . ':5000/node-sync'
        );

        $statusCode = $response->getStatusCode();

        if($statusCode === 200 || $statusCode === 418) // haha - teapots are funny
        {
            $isSynced = (trim($response->getContent(false)) !== 'false');
        }

        /******** Check blockchain height ********/
        $data = $this->harmonyClient->hmy_getNodeMetadata();
        $blockChainHeight = isset($data['current-block-number']) ? (int) $data['current-block-number'] : null;

        /******** Check our node height ********/
        $data = $this->harmonyClient->hmy_getNodeMetadata('http://' . $node->getPrivateIp() . ':9500');
        $height = isset($data['current-block-number']) ? (int) $data['current-block-number'] : null;


        return new NodeInfo(
            $time,
            $isSynced,
            $height,
            $blockChainHeight,
            $node
        );
    }

}
