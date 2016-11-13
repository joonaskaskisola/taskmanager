<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Invoice;
use Doctrine\ORM\Mapping as ORM;

/**
 * InvoiceRow
 *
 * @ORM\Table(name="invoice_row")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InvoiceRowRepository")
 */
class InvoiceRow
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
     * @ORM\ManyToOne(targetEntity="Invoice")
     * @ORM\JoinColumn(name="invoice", referencedColumnName="id")
     */
    private $invoice;

    /**
     * @ORM\ManyToOne(targetEntity="Task")
     * @ORM\JoinColumn(name="task", referencedColumnName="id")
     */
    private $task;

    /**
     * @var string
     *
     * @ORM\Column(name="totalSum", type="decimal", precision=10, scale=2)
     */
    private $totalSum;

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
     * Set task
     *
     * @param Task $task
     * @return InvoiceRow
     */
    public function setTask($task)
    {
        $this->task = $task;

        return $this;
    }

    /**
     * Get task
     *
     * @return integer 
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * Set totalSum
     *
     * @param string $totalSum
     * @return InvoiceRow
     */
    public function setTotalSum($totalSum)
    {
        $this->totalSum = $totalSum;

        return $this;
    }

    /**
     * Get totalSum
     *
     * @return string 
     */
    public function getTotalSum()
    {
        return $this->totalSum;
    }

    /**
     * Sets a new invoice
     * @param null|Object|Invoice $invoice
     */
    public function setInvoice($invoice) {
        $this->invoice = $invoice;
    }

    /**
     * @return mixed
     */
    public function getInvoice()
    {
        return $this->invoice;
    }
}
