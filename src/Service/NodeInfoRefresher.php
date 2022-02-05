<?php


namespace App\Service;


use App\Entity\Node;
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
     * @var PoktValidatorService
     */
    private $poktValidatorService;

    /**
     * NodeInfoRefresher constructor.
     * @param NodeInfoService $nodeInfoService
     * @param EntityManagerInterface $em
     * @param NodeRepository $nodeRepo
     * @param PoktValidatorService $poktValidatorService
     */
    public function __construct(NodeInfoService $nodeInfoService, EntityManagerInterface $em, NodeRepository $nodeRepo, PoktValidatorService $poktValidatorService)
    {
        $this->nodeInfoService = $nodeInfoService;
        $this->em = $em;
        $this->nodeRepo = $nodeRepo;
        $this->poktValidatorService = $poktValidatorService;
    }

    public function refresh()
    {
        $nodes = $this->nodeRepo->findAll();

        foreach ($nodes as $node)
        {
            try {
                $nodeInfo = $this->nodeInfoService->getNodeInfo($node);
                $this->em->persist($nodeInfo);

                // refresh rewards for pocket nodes
                if($node->getType() === Node::TYPE_POCKET && $nodeInfo->getValidatorAddress() !== null)
                {
                    $poktValidator = $this->poktValidatorService->getPoktValidator($nodeInfo->getValidatorAddress());
                    $node->setValidator($poktValidator);
                    $this->poktValidatorService->refreshRewards($poktValidator);
                }
            }
            catch (\Exception $e)
            {
                error_log('Y U NO WORK node#' . $node->getId());
            }
        }

        $this->em->flush();

        return count($nodes);
    }

}