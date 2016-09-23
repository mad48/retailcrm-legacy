<?php

class PaymentTypesHandler implements HandlerInterface
{
    public function prepare($types)
    {
        return $types;
    }
}