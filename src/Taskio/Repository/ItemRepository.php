<?php

namespace Taskio\Repository;

/**
 * ItemRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ItemRepository extends AbstractRepository
{
    public function insertItemToAllCustomers(Item $item)
    {
        $item = new Item();
        $item
            ->setName($request->request->get('name'))
            ->setPrice($request->request->get('price'))
            ->setCategory(
                $this
                    ->getDoctrine()
                    ->getRepository('Taskio:Category')
                    ->findOneBy([
                        'id' => $request->request->get('category')
                    ])
            )
            ->setUnit(
                $this
                    ->getDoctrine()
                    ->getRepository('Taskio:Unit')
                    ->findOneBy([
                        'id' => $request->request->get('unit')
                    ])
            );

        $this->persist($item);

        array_map(function ($customer) use ($item, $repository) {
            $customerItem = new CustomerItem();
            $customerItem
                ->setCustomer($customer)
                ->setItem($item)
                ->setPrice($item->getPrice());
            $repository->persist($customerItem);
        }, $this
            ->getDoctrine()
            ->getRepository('Taskio:Customer')
            ->findAll()
        );

    }
}
