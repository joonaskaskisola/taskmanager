<?php

namespace Taskio\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ip
 *
 * @ORM\Table(name="ip")
 * @ORM\Entity(repositoryClass="Taskio\Repository\IpRepository")
 */
class Ip extends AbstractEntity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, unique=true)
     */
    private $address;

    /**
     * @var array
     *
     * @ORM\Column(name="whois", type="json_array")
     */
    private $whois;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Ip
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set whois
     *
     * @param array $whois
     *
     * @return Ip
     */
    public function setWhois($whois)
    {
        $this->whois = $whois;

        return $this;
    }

    /**
     * Get whois
     *
     * @return array
     */
    public function getWhois()
    {
        return $this->whois;
    }
}

