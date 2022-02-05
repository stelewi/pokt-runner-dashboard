<?php

namespace App\Entity;

use App\Repository\NodeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Reward
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $cliamTxId;


    /**
     * @ORM\Column(type="float")
     */
    private $amount;


    /**
     * @var PoktValidator
     * @ORM\ManyToOne(targetEntity=PoktValidator::class, inversedBy="rewards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $poktValidator;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $height;

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
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getCliamTxId()
    {
        return $this->cliamTxId;
    }

    /**
     * @param mixed $cliamTxId
     */
    public function setCliamTxId($cliamTxId): void
    {
        $this->cliamTxId = $cliamTxId;
    }

    /**
     * @return PoktValidator
     */
    public function getPoktValidator(): PoktValidator
    {
        return $this->poktValidator;
    }

    /**
     * @param PoktValidator $poktValidator
     */
    public function setPoktValidator(PoktValidator $poktValidator): void
    {
        $this->poktValidator = $poktValidator;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @param int $height
     */
    public function setHeight(int $height): void
    {
        $this->height = $height;
    }




}
