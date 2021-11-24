<?php

namespace App\Entity;

use App\Repository\NodeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NodeRepository::class)
 */
class Node
{
    const TYPE_HARMONY = 'harmony';
    const TYPE_POCKET = 'pocket';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hostname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $privateIp;

    /**
     * @ORM\OneToMany(targetEntity=NodeInfo::class, mappedBy="node", orphanRemoval=true)
     */
    private $infos;

    public function __construct()
    {
        $this->infos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getHostname(): ?string
    {
        return $this->hostname;
    }

    public function setHostname(string $hostname): self
    {
        $this->hostname = $hostname;

        return $this;
    }

    public function getPrivateIp(): ?string
    {
        return $this->privateIp;
    }

    public function setPrivateIp(string $privateIp): self
    {
        $this->privateIp = $privateIp;

        return $this;
    }

    /**
     * @return Collection|NodeInfo[]
     */
    public function getInfos(): Collection
    {
        return $this->infos;
    }

    public function addInfo(NodeInfo $info): self
    {
        if (!$this->infos->contains($info)) {
            $this->infos[] = $info;
            $info->setNode($this);
        }

        return $this;
    }

    public function removeInfo(NodeInfo $info): self
    {
        if ($this->infos->removeElement($info)) {
            // set the owning side to null (unless already changed)
            if ($info->getNode() === $this) {
                $info->setNode(null);
            }
        }

        return $this;
    }
}
