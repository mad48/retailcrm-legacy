<?php

class ExtendedOrdersBuilder extends Builder
{
    /**
     * Get all orders
     *
     * @return array
     */
    public function buildOrders()
    {echo "buildOrders";
        $query = $this->rule->getSQL('orders');
        $handler = $this->rule->getHandler('OrdersHandler');
        $this->sql = $this->container->db->prepare($query);

        return $this->build($handler);
    }

    /**
     * Get all new orders since last run
     *
     * @return array
     */
    public function buildOrdersLast()
    {echo "buildOrdersLast";
        $lastSync = DataHelper::getDate($this->container->ordersLog);

        $query = $this->rule->getSQL('orders_last');
        $handler = $this->rule->getHandler('OrdersHandler');
        $this->sql = $this->container->db->prepare($query);
        $this->sql->bindParam(':lastSync', $lastSync);

        return $this->build($handler);
    }

    /**
     * Get new orders by id
     *
     * @return array
     */
    public function buildOrdersById($uidString)
    {echo "buildOrdersById";
        $query = $this->rule->getSQL('orders_uid');
        $handler = $this->rule->getHandler('OrdersHandler');
        $this->sql = $this->container->db->prepare($query);
        $uids = DataHelper::explodeUids($uidString);
        $this->sql->bindParam(':orderIds', $uids);

        return $this->build($handler);
    }

    /**
     * Get all updated orders since last run
     *
     * @return array
     */
    public function buildOrdersUpdate()
    {echo "buildOrdersLast";
        $lastSync = DataHelper::getDate($this->container->ordersUpdatesLog);

        $query = $this->rule->getSQL('orders_update_last');
        $handler = $this->rule->getHandler('OrdersUpdateHandler');
        $this->sql = $this->container->db->prepare($query);
        $this->sql->bindParam(':lastSync', $lastSync);

        return $this->build($handler);
    }

    /**
     * Get updated orders by id
     *
     * @return array
     */
    public function buildOrdersUpdateById($uidString)
    {echo "buildOrdersUpdateById";
        $uids = DataHelper::explodeUids($uidString);
        $query = $this->rule->getSQL('orders_update_uid');
        $handler = $this->rule->getHandler('OrdersUpdateHandler');
        $this->sql = $this->container->db->prepare($query);
        $this->sql->bindParam(':orderIds', $uids);

        return $this->build($handler);
    }

    /**
     * Custom update
     *
     * @return array
     */
    public function buildOrdersCustomUpdate()
    {echo "buildOrdersCustomUpdate";
        $query = $this->rule->getSQL('orders_update_custom');
        $handler = $this->rule->getHandler('OrdersCustomUpdateHandler');
        $this->sql = $this->container->db->prepare($query);

        return $this->build($handler);
    }
}
