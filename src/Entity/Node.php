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
    const TYPE_ETH_MAIN = 'eth-mainnet';

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

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $rpcPort;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $secondaryRpcPort;

    /**
     * @var PoktValidator|null
     * @ORM\OneToOne(targetEntity=PoktValidator::class)
     */
    private $validator = null;

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

    public function getRpcPort(): ?string
    {
        return $this->rpcPort;
    }

    public function setRpcPort(?string $rpcPort): self
    {
        $this->rpcPort = $rpcPort;

        return $this;
    }

    public function getSecondaryRpcPort(): ?string
    {
        return $this->secondaryRpcPort;
    }

    public function setSecondaryRpcPort(?string $secondaryRpcPort): self
    {
        $this->secondaryRpcPort = $secondaryRpcPort;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValidatorAddress()
    {
        return $this->validatorAddress;
    }

    /**
     * @param mixed $validatorAddress
     */
    public function setValidatorAddress($validatorAddress): void
    {
        $this->validatorAddress = $validatorAddress;
    }

    /**
     * @return PoktValidator
     */
    public function getValidator(): ?PoktValidator
    {
        return $this->validator;
    }

    /**
     * @param PoktValidator $validator
     */
    public function setValidator(?PoktValidator $validator): void
    {
        $this->validator = $validator;
    }
}
