<?php


namespace App\Service;


use App\Repository\NodeRepository;
use Doctrine\ORM\EntityManagerInterface;

class NodeInfoRefresher
{
    /**
     * @var NodeInfoService
     */
    private $nodeInfoService;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var NodeRepository
     */
    private $nodeRepo;

    /**
     * NodeInfoRefresher constructor.
     * @param NodeInfoService $nodeInfoService
     * @param EntityManagerInterface $em
     * @param NodeRepository $nodeRepo
     */
    public function __construct(NodeInfoService $nodeInfoService, EntityManagerInterface $em, NodeRepository $nodeRepo)
    {
        $this->nodeInfoService = $nodeInfoService;
        $this->em = $em;
        $this->nodeRepo = $nodeRepo;
    }

    public function refresh()
    {
        $nodes = $this->nodeRepo->findAll();

        foreach ($nodes as $node)
        {
            $nodeInfo = $this->nodeInfoService->getNodeInfo($node);

            $this->em->persist($nodeInfo);
        }

        $this->em->flush();

        return count($nodes);
    }

}