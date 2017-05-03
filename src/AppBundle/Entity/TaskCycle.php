<?php

namespace AppBundle\Entity;

use Cake\Chronos\Chronos;
use DateInterval;
use Doctrine\ORM\Mapping as ORM;

/**
 * TaskCycle
 *
 * @ORM\Table(name="task_cycle")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TaskCycleRepository")
 */
class TaskCycle extends AbstractEntity
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="hour", type="integer")
     */
    private $hour;

    /**
     * @var string
     *
     * @ORM\Column(name="day", type="integer")
     */
    private $day;

    /**
     * @var int
     *
     * @ORM\Column(name="week", type="integer")
     */
    private $week;

    /**
     * @var int
     *
     * @ORM\Column(name="month", type="integer")
     */
    private $month;

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
     * Set name
     *
     * @param string $name
     *
     * @return TaskCycle
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set hour
     *
     * @param integer $hour
     *
     * @return TaskCycle
     */
    public function setHour($hour)
    {
        $this->hour = $hour;

        return $this;
    }

    /**
     * Get hour
     *
     * @return int
     */
    public function getHour()
    {
        return $this->hour;
    }

    /**
     * Set day
     *
     * @param int $day
     *
     * @return TaskCycle
     */
    public function setDay($day)
    {
        $this->day = $day;

        return $this;
    }

    /**
     * Get day
     *
     * @return int
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * Set week
     *
     * @param integer $week
     *
     * @return TaskCycle
     */
    public function setWeek($week)
    {
        $this->week = $week;

        return $this;
    }

    /**
     * Get week
     *
     * @return int
     */
    public function getWeek()
    {
        return $this->week;
    }

    /**
     * Set month
     *
     * @param integer $month
     *
     * @return TaskCycle
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * Get month
     *
     * @return int
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @param Chronos $date
     * @return Chronos
     */
    public function getModifiedDate(Chronos $date)
    {
        if ($this->getHour() > 0) {
            $date->add(new DateInterval(sprintf("P%sH", $this->getHour())));
        }

        if ($this->getDay() > 0) {
            $date->add(new DateInterval(sprintf("P%sD", $this->getDay())));
        }

        if ($this->getWeek() > 0) {
            $date->add(new DateInterval(sprintf("P%sW", $this->getWeek())));
        }

        if ($this->getMonth() > 0) {
            $date->add(new DateInterval(sprintf("P%sM", $this->getMonth())));
        }

        return $date;
    }
}

