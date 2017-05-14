<?php

namespace Taskio\Entity;

use Cake\Chronos\Chronos;
use Doctrine\ORM\Mapping as ORM;

/**
 * UserIpLog
 *
 * @ORM\Table(name="user_ip_log")
 * @ORM\Entity(repositoryClass="Taskio\Repository\UserIpLogRepository")
 */
class UserIpLog extends AbstractEntity
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
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Ip")
     * @ORM\JoinColumn(name="ip", referencedColumnName="id")
     */
    private $ip;

    /**
     * @var Chronos
     *
     * @ORM\Column(name="created_at", type="datetimetz")
     */
    private $createdAt;

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
     * Set user
     *
     * @param User $user
     *
     * @return UserIpLog
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set ip
     *
     * @param Ip $ip
     *
     * @return UserIpLog
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return Ip
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @return Chronos
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param Chronos $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}

