<?php


namespace App\Data;


class NodeInfo
{
    /**
     * @var string
     */
    private $time;

    /**
     * @var bool|null
     */
    private $isSynced;

    /**
     * @var integer|null
     */
    private $height;

    /**
     * @var integer|null
     */
    private $blockChainHeight;

    /**
     * NodeInfo constructor.
     * @param string $time
     * @param bool|null $isSynced
     * @param int|null $height
     * @param int|null $blockChainHeight
     */
    public function __construct(string $time, ?bool $isSynced, ?int $height, ?int $blockChainHeight)
    {
        $this->time = $time;
        $this->isSynced = $isSynced;
        $this->height = $height;
        $this->blockChainHeight = $blockChainHeight;
    }


    /**
     * @return string
     */
    public function getTime(): string
    {
        return $this->time;
    }

    /**
     * @return bool|null
     */
    public function getIsSynced(): ?bool
    {
        return $this->isSynced;
    }

    /**
     * @return int|null
     */
    public function getHeight(): ?int
    {
        return $this->height;
    }

    /**
     * @return int|null
     */
    public function getBlockChainHeight(): ?int
    {
        return $this->blockChainHeight;
    }
}