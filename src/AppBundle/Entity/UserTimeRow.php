<?php

namespace AppBundle\Entity;

use Cake\Chronos\Chronos;
use Doctrine\ORM\Mapping as ORM;

/**
 * UserTimeRow
 *
 * @ORM\Table(name="user_time_row")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserTimeRowRepository")
 */
class UserTimeRow extends AbstractEntity
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
     * @var Chronos
     *
     * @ORM\Column(name="start_datetime", type="datetimetz")
     */
    private $startDateTime;

    /**
     * @var Chronos
     *
     * @ORM\Column(name="end_datetime", type="datetimetz", nullable=true)
     */
    private $endDateTime;

    /**
     * @var Chronos
     *
     * @ORM\Column(name="duration", type="time", nullable=true)
     */
    private $duration;

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
     * @return UserTimeRow
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
     * Set startDateTime
     *
     * @param Chronos $startDateTime
     *
     * @return UserTimeRow
     */
    public function setStartDateTime($startDateTime)
    {
        $this->startDateTime = $startDateTime;

        return $this;
    }

    /**
     * Get startDateTime
     *
     * @return Chronos
     */
    public function getStartDateTime()
    {
        return $this->startDateTime;
    }

    /**
     * Set endDateTime
     *
     * @param Chronos $endDateTime
     *
     * @return UserTimeRow
     */
    public function setEndDateTime($endDateTime)
    {
        $this->endDateTime = $endDateTime;

        return $this;
    }

    /**
     * Get endDateTime
     *
     * @return Chronos
     */
    public function getEndDateTime()
    {
        return $this->endDateTime;
    }

    /**
     * Set duration
     *
     * @param Chronos $duration
     *
     * @return UserTimeRow
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return Chronos
     */
    public function getDuration()
    {
        return $this->duration;
    }
}

