<?php


namespace App\Service;


use App\Data\Node;
use App\Data\NodeInfo;
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
     * NodeInfoService constructor.
     * @param HttpClientInterface $httpClient
     * @param HarmonyClient $harmonyClient
     */
    public function __construct(HttpClientInterface $httpClient, HarmonyClient $harmonyClient)
    {
        $this->httpClient = $httpClient;
        $this->harmonyClient = $harmonyClient;
    }

    public function getNodeInfo(Node $node): NodeInfo
    {
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

        return new NodeInfo(
            $time->format('c'),
            null,
            null,
            null
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
            $time->format('c'),
            $isSynced,
            $height,
            $blockChainHeight
        );
    }

}