<?php

namespace Taskio\Repository;

use Taskio\Entity\InvoiceRow;
use Taskio\Entity\Task;

/**
 * InvoiceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class InvoiceRepository extends AbstractRepository
{
    /**
     * @param integer $invoiceId
     * @param array $tasks
     */
    public function setTasksPaid($invoiceId, array $tasks)
    {
        $em = $this->getEntityManager();

        $taskRepository = $em->getRepository('Taskio:Task');
        $invoice = $this->find($invoiceId);

        $invoiceSum = 0.0;
        foreach ($tasks as $taskId) {
            /** @var Task $task */
            $task = $taskRepository->findOneBy(array('id' => $taskId));
            $task->setInvoice($invoice);
            $em->persist($task);

            $invoiceRow = new InvoiceRow();
            $invoiceRow->setInvoice($invoice);
            $invoiceRow->setTask($task);
            $invoiceRow->setTotalSum($task->getPrice());
            $invoiceSum += $task->getPrice();
            $em->persist($invoiceRow);
        }

        $invoice->setPrice($invoiceSum);
        $em->persist($invoice);

        $em->flush();
    }
}
