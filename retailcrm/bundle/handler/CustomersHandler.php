<?php

class CustomersHandler implements HandlerInterface
{
    public function prepare($data)
    {
        $customers = array();

        foreach ($data as $customer) {
            $customers[] = array(
                'id' => $customer['id'],
				'externalId' => $customer['externalId'],
                'firstName' => $customer['firstName'],
				'email' => $customer['email'],
				'createdAt' => $customer['createdAt'],
				'vip' => $customer['vip'],
				'personalDiscount' => $customer['personalDiscount']
            );
        }

        return $customers;
    }
}