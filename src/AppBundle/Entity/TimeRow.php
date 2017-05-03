<?php

namespace AppBundle\Entity;

use Cake\Chronos\Chronos;
use Doctrine\ORM\Mapping as ORM;

/**
 * TimeRow
 *
 * @ORM\Table(name="time_row", uniqueConstraints={@ORM\UniqueConstraint(name="user_date", columns={"user", "date"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TimeRowRepository")
 */
class TimeRow extends AbstractEntity
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
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var Chronos
     *
     * @ORM\Column(name="start_time", type="time", nullable=true)
     */
    private $startTime;

    /**
     * @var Chronos
     *
     * @ORM\Column(name="end_time", type="time", nullable=true)
     */
    private $endTime;

    /**
     * @var array
     *
     * @ORM\Column(name="data", type="json_array", nullable=true)
     */
    private $data;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var Chronos
     *
     * @ORM\Column(name="lunch", type="time", nullable=true)
     */
    private $lunch;

    /**
     * @var Chronos
     *
     * @ORM\Column(name="sickleave", type="time", nullable=true)
     */
    private $sickleave;

    /**
     * @ORM\ManyToOne(targetEntity="TimeVacation")
     * @ORM\JoinColumn(name="vacation", referencedColumnName="id", nullable=true)
     */
    private $vacation;

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
     * @param \stdClass $user
     *
     * @return TimeRow
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \stdClass
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set date
     *
     * @param Chronos $date
     *
     * @return TimeRow
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return Chronos
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set startTime
     *
     * @param Chronos $startTime
     *
     * @return TimeRow
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime
     *
     * @return Chronos
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set endTime
     *
     * @param Chronos $endTime
     *
     * @return TimeRow
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * Get endTime
     *
     * @return Chronos
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * Set data
     *
     * @param array $data
     *
     * @return TimeRow
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $description
     * @return TimeRow
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param Chronos $lunch
     * @return TimeRow
     */
    public function setLunch($lunch)
    {
        $this->lunch = $lunch;

        return $this;
    }

    /**
     * @return Chronos
     */
    public function getLunch()
    {
        return $this->lunch;
    }

    /**
     * @param Chronos $sickleave
     * @return TimeRow
     */
    public function setSickleave($sickleave)
    {
        $this->sickleave = $sickleave;

        return $this;
    }

    /**
     * @return Chronos
     */
    public function getSickleave()
    {
        return $this->sickleave;
    }

    /**
     * @param mixed $vacation
     * @return TimeRow
     */
    public function setVacation($vacation)
    {
        $this->vacation = $vacation;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVacation()
    {
        return $this->vacation;
    }
}

