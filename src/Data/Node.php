<?php


namespace App\Data;


class Node
{
    const TYPE_HARMONY = 'harmony';
    const TYPE_POCKET = 'pocket';

    /**
     * @var string
     */
    private $type;

    /**
     * @var
     */
    private $name;

    /**
     * @var string
     */
    private $hostname;

    /**
     * Node constructor.
     * @param string $type
     * @param $name
     * @param string $hostname
     */
    public function __construct(string $type, $name, string $hostname)
    {
        $this->type = $type;
        $this->name = $name;
        $this->hostname = $hostname;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getHostname(): string
    {
        return $this->hostname;
    }


}