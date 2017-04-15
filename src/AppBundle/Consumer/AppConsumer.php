<?php

namespace AppBundle\Consumer;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Monolog\Logger;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use AppBundle\Entity\Item;
use AppBundle\Entity\CustomerItem;
use Symfony\Component\HttpFoundation\ParameterBag;

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
        $action = null;

        switch ($body['event']) {
            case 'new_item':
                $action = '_handleNewItem';
                $this->_handleNewItem($body['request']);
                break;
            default:
                return false;
        }

        try {
            call_user_func([$this, $action], $body['request']);
            fwrite(STDOUT, $action . ' finished' . PHP_EOL);
        } catch (\Exception $e) {
            fwrite(STDOUT, 'The unexpected happened: ' . $e->getMessage() . PHP_EOL .  $e->getTraceAsString() . PHP_EOL);
        }

        return true;
    }

    /**
     * @param $request
     */
    private function _handleNewItem(ParameterBag $request)
    {
        $item = (new Item())
            ->setName($request->get('name'))
            ->setPrice($request->get('price'))
            ->setCategory(
                $this
                    ->doctrine
                    ->getRepository('AppBundle:Category')
                    ->findOneBy([
                        'id' => $request->get('category')
                    ])
            )
            ->setUnit(
                $this
                    ->doctrine
                    ->getRepository('AppBundle:Unit')
                    ->findOneBy([
                        'id' => $request->get('unit')
                    ])
            );

        $this->doctrine->getManager()->persist($item);
        $this->doctrine->getManager()->flush();

        array_map(function ($customer) use ($item) {
            $customerItem = new CustomerItem();
            $customerItem
                ->setCustomer($customer)
                ->setItem($item)
                ->setPrice($item->getPrice());

            $this->doctrine->getManager()->persist($customerItem);
        }, $this->doctrine->getRepository('AppBundle:Customer')->findAll());

        $this->doctrine->getManager()->flush();
    }
}
