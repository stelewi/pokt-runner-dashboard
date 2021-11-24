<?php

namespace App\Entity;

use App\Repository\NodeInfoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NodeInfoRepository::class)
 */
class NodeInfo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $time;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isSynced;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $height;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $blockChainHeight;

    /**
     * @ORM\ManyToOne(targetEntity=Node::class, inversedBy="infos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $node;

    /**
     * NodeInfo constructor.
     * @param $time
     * @param $isSynced
     * @param $height
     * @param $blockChainHeight
     * @param $node
     */
    public function __construct($time, $isSynced, $height, $blockChainHeight, $node)
    {
        $this->time = $time;
        $this->isSynced = $isSynced;
        $this->height = $height;
        $this->blockChainHeight = $blockChainHeight;
        $this->node = $node;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getIsSynced(): ?bool
    {
        return $this->isSynced;
    }

    public function setIsSynced(?bool $isSynced): self
    {
        $this->isSynced = $isSynced;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getBlockChainHeight(): ?int
    {
        return $this->blockChainHeight;
    }

    public function setBlockChainHeight(?int $blockChainHeight): self
    {
        $this->blockChainHeight = $blockChainHeight;

        return $this;
    }

    public function getNode(): ?Node
    {
        return $this->node;
    }

    public function setNode(?Node $node): self
    {
        $this->node = $node;

        return $this;
    }
}
