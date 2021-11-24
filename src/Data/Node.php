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
     * @var string
     */
    private $privateIp;

    /**
     * Node constructor.
     * @param string $type
     * @param $name
     * @param string $hostname
     * @param string $privateIp
     */
    public function __construct(string $type, $name, string $hostname, string $privateIp)
    {
        $this->type = $type;
        $this->name = $name;
        $this->hostname = $hostname;
        $this->privateIp = $privateIp;
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

    /**
     * @return string
     */
    public function getPrivateIp(): string
    {
        return $this->privateIp;
    }


}