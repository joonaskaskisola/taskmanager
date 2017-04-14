<?php

namespace AppBundle\Consumer;

use Doctrine\Bundle\DoctrineBundle\Registry;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use AppBUndle\Entity\Item;
use AppBundle\Entity\CustomerItem;

class AppConsumer implements ConsumerInterface
{
    /**
     * @var Registry
     */
    private $doctrine;

    /**
     * AppConsumer constructor.
     * @param Registry $doctrine
     */
    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @param AMQPMessage $msg
     * @return bool
     */
    public function execute(AMQPMessage $msg)
    {
        $body = unserialize($msg->body);

        switch ($body['event']) {
            case 'new_item':
                $this->_handleNewItem($body['entity']);
                break;
        }

        return true;
    }

    /**
     * @param Item $item
     */
    private function _handleNewItem(Item $item)
    {
        /** @var ItemRepository $itemRepository */
        $itemRepository = $this->doctrine->getRepository('AppBundle:Item');

        array_map(function ($customer) use ($item, $itemRepository) {
            $customerItem = new CustomerItem();
            $customerItem
                ->setCustomer($customer)
                ->setItem($item)
                ->setPrice($item->getPrice());
            $this->doctrine->getManager()->persist($customerItem);
        }, $this->doctrine->getRepository('AppBundle:Customer')->findAll());
    }
}