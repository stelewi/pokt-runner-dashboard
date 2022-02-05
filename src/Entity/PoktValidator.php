<?php

namespace App\Entity;

use App\Repository\NodeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ValidatorRepository::class)
 */
class PoktValidator
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $validatorAddress;

    /**
     * @ORM\OneToMany(targetEntity=Reward::class, mappedBy="poktValidator", orphanRemoval=true)
     */
    private $rewards;

    /**
     * @ORM\Column(type="float")
     */
    private $totalRewards;


    public function __construct()
    {
        $this->rewards = new ArrayCollection();
        $this->totalRewards = 0;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
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

    public function getRewards()
    {
        return $this->rewards;
    }


    /**
     * @return mixed
     */
    public function getTotalRewards()
    {
        return $this->totalRewards;
    }

    /**
     * @param mixed $totalRewards
     */
    public function setTotalRewards($totalRewards): void
    {
        $this->totalRewards = $totalRewards;
    }
}
