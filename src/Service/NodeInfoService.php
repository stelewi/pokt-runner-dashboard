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
     * @var NodeInfoRepository
     */
    private $nodeInfoRepo;

    /**
     * NodeInfoService constructor.
     * @param HttpClientInterface $httpClient
     * @param HarmonyClient $harmonyClient
     * @param PocketClient $pocketClient
     * @param NodeInfoRepository $nodeInfoRepo
     */
    public function __construct(HttpClientInterface $httpClient, HarmonyClient $harmonyClient, PocketClient $pocketClient, NodeInfoRepository $nodeInfoRepo)
    {
        $this->httpClient = $httpClient;
        $this->harmonyClient = $harmonyClient;
        $this->pocketClient = $pocketClient;
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

            default:
                throw new \Exception('Unknown Node Type');
        }
    }

    private function getPoktNodeInfo(Node $node): NodeInfo
    {
        $time = new \DateTimeImmutable();
        $isSynced = null;
        $height = null;
        $blockChainHeight = null;

        /******** Check blockchain height ********/
//        $data = $this->pocketClient->nodeStatus('http://' . $node->getHostname() . ':26657/');
//
//        if($data !== null)
//        {
//            $blockChainHeight = $data['sync_info']['latest_block_height'];
//        }

        /******** Check our node  ********/
        $data = $this->pocketClient->nodeStatus('http://' . $node->getPrivateIp() . ':26657/');

        if($data !== null)
        {
            $isSynced = !$data['sync_info']['catching_up'];
            $height = $data['sync_info']['latest_block_height'];
        }

        return new NodeInfo(
            $time,
            $isSynced,
            $height,
            $blockChainHeight,
            $node
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