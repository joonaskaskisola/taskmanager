<?php

namespace AppBundle\Entity;

use Cake\Chronos\Chronos;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Customer;
use AppBundle\Entity\Invoice;
use AppBundle\Entity\TaskCycle;

/**
 * Task
 *
 * @ORM\Table(name="task")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TaskRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Task
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
     * @ORM\ManyToOne(targetEntity="Customer")
     * @ORM\JoinColumn(name="customer", referencedColumnName="id")
     */
    private $customer;

    /**
     * @ORM\ManyToOne(targetEntity="CustomerItem")
     * @ORM\JoinColumn(name="customer_item", referencedColumnName="id")
     */
    private $customerItem;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var boolean
     *
     * @ORM\Column(name="important", type="integer", length=1)
     */
    private $important;

    /**
     * @var Chronos
     *
     * @ORM\Column(name="created_at", type="datetimetz")
     */
    private $createdAt;

    /**
     * @var Chronos
     *
     * @ORM\Column(name="next_at", type="datetimetz", nullable=true)
     */
    private $nextAt = null;

    /**
     * @var Chronos
     *
     * @ORM\Column(name="modified_at", type="datetimetz", nullable=true)
     */
    private $modifiedAt = null;

    /**
     * @var null|Chronos
     *
     * @ORM\Column(name="ended_at", type="datetimetz", nullable=true)
     */
    private $endedAt = null;

    /**
     * @ORM\ManyToOne(targetEntity="TaskCycle")
     * @ORM\JoinColumn(name="cycle_time", referencedColumnName="id")
     */
    private $cycleTime;

    /**
     * @ORM\ManyToOne(targetEntity="TaskStatus")
     * @ORM\JoinColumn(name="status", referencedColumnName="id")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="Task")
     * @ORM\JoinColumn(name="next_task", referencedColumnName="id")
     */
    private $nextTask = null;

    /**
     * @ORM\ManyToOne(targetEntity="Invoice")
     * @ORM\JoinColumn(name="invoice", referencedColumnName="id")
     */
    private $invoice = null;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2)
     */
    private $price = 0.0;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     */
    private $user;

    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="decimal", precision=10, scale=1)
     */
    private $amount = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="total_price", type="decimal", precision=10, scale=2)
     */
    private $totalPrice = 0.0;

    /**
     * Task constructor.
     */
    public function __construct()
    {
        $this->createdAt = new Chronos();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set createdAt
     *
     * @param Chronos $createdAt
     * @return Task
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return Chronos 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set nextAt
     *
     * @param Chronos $nextAt
     * @return Task
     */
    public function setNextAt($nextAt)
    {
        $this->nextAt = $nextAt;

        return $this;
    }

    /**
     * Get nextAt
     *
     * @return Chronos 
     */
    public function getNextAt()
    {
        return $this->nextAt;
    }

    /**
     * Set modifiedAt
     *
     * @param Chronos $modifiedAt
     * @return Task
     */
    public function setModifiedAt($modifiedAt)
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    /**
     * Get modifiedAt
     *
     * @return Chronos 
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }

    /**
     * Set endedAt
     *
     * @param Chronos $endedAt
     * @return Task
     */
    public function setEndedAt($endedAt)
    {
        $this->endedAt = $endedAt;

        return $this;
    }

    /**
     * Get endedAt
     *
     * @return Chronos 
     */
    public function getEndedAt()
    {
        return $this->endedAt;
    }

    /**
     * Set cycleTime
     *
     * @param TaskCycle $cycleTime
     * @return Task
     */
    public function setCycleTime($cycleTime)
    {
        $this->cycleTime = $cycleTime;

        return $this;
    }

    /**
     * Get cycleTime
     *
     * @return TaskCycle
     */
    public function getCycleTime()
    {
        return $this->cycleTime;
    }

    /**
     * Set status
     *
     * @param TaskStatus|object $status
     * @return Task
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return TaskStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $customer
     * @return Task
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param mixed $nextTask
     * @return Task
     */
    public function setNextTask($nextTask)
    {
        $this->nextTask = $nextTask;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNextTask()
    {
        return $this->nextTask;
    }

    /**
     * @param boolean $important
     * @return Task
     */
    public function setImportant($important)
    {
        if ($important === true) {
            $important = 1;
        } elseif ($important === false) {
            $important = 0;
        }

        $this->important = $important;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getImportant()
    {
        if ($this->important === 1) {
            return true;
        } elseif ($this->important === 0) {
            return false;
        }

        return $this->important;
    }

    /**
     * @param Invoice|Object $invoice
     * @return Task
     */
    public function setInvoice($invoice)
    {
        $this->invoice = $invoice;

        return $this;
    }

    /**
     * @return null|Invoice
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * @param float $price
     * @return Task
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $user
     * @return Task
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param float $totalPrice
     * @return Task
     */
    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    /**
     * @param float $amount
     * @return Task
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param string $description
     * @return Task
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
     * @return CustomerItem
     */
    public function getCustomerItem()
    {
        return $this->customerItem;
    }

    /**
     * @param CustomerItem $customerItem
     */
    public function setCustomerItem($customerItem)
    {
        $this->customerItem = $customerItem;
    }
}
