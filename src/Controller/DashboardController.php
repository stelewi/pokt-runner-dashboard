<?php

namespace App\Controller;

use App\Data\Node;
use App\Service\NodeInfoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="dashboard")
     */
    public function index(NodeInfoService $nodeInfoService): Response
    {
        $nodes = [
            new Node(Node::TYPE_HARMONY, 'Harmony Explorer Node #1', '178.62.116.190'),
            new Node(Node::TYPE_POCKET, 'Pocket Node #1', 'node1.pokt.online'),
        ];

        $nodeData = array_map(function (Node $node) use ($nodeInfoService) {
            return [
                'node' => $node,
                'info' => $nodeInfoService->getNodeInfo($node)
            ];
        }, $nodes);

        return $this->render('dashboard/index.html.twig', [
            'data' => $nodeData
        ]);
    }
}
