<?php

namespace App\Controller;

use App\Entity\Node;
use App\Repository\NodeInfoRepository;
use App\Repository\NodeRepository;
use App\Service\NodeInfoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="dashboard")
     */
    public function index(
        NodeRepository $nodeRepository,
        NodeInfoService $nodeInfoService
    ): Response {


        $nodes = $nodeRepository->findAll();

        $nodeData = array_map(function (Node $node) use ($nodeInfoService) {
            return [
                'node' => $node,
                'info' => $nodeInfoService->getNodeInfo($node, false)
            ];
        }, $nodeRepository->findAll());

        return $this->render('dashboard/index.html.twig', [
            'data' => $nodeData
        ]);
    }

    /**
     * @Route("/node-info/{id}", name="node_info")
     */
    public function node(
        Node $node,
        NodeInfoRepository $nodeInfoRepository
    ): Response {

        $infos = $nodeInfoRepository->findBy(
            ['node' => $node],
            ['time' => 'DESC'],
            1000
        );

        return $this->render('dashboard/node-info.html.twig', [
            'node' => $node,
            'infos' => $infos
        ]);
    }



}
