<?php

class OrdersHistoryHandler implements HandlerInterface
{
    public function prepare($data)
    {
        $this->container = Container::getInstance();

        $this->logger = new Logger();
        $this->rule = new Rule();

        $this->api = new RequestProxy(
            $this->container->settings['api']['url'],
            $this->container->settings['api']['key']
        );

        $orderGroups = $this->api->statusGroupsList();

        if (!is_null($orderGroups)) {
            $isCanceled = $orderGroups['statusGroups']['cancel']['statuses'];
        }

        $update = $this->rule->getSQL('orders_history_update');
        $create = $this->rule->getSQL('orders_history_create');

        foreach($data as $record) {
            if (empty($record['firstName'])) continue;
			var_dump($record);

			if (!empty($record['externalId'])) {
                $this->sql = $this->container->db->prepare($update);
                $this->sql->bindParam(':externalId', $record['externalId']);//echo "update";
            } else {
                $this->sql = $this->container->db->prepare($create);
                if (!empty($record['createdAt'])) {
                    $this->sql->bindParam(':createdAt', $record['createdAt']);//echo "create";
                } else {
                    $this->sql->bindParam(':createdAt', $var = NULL);
                }
            }
/*
			if (!empty($record['number'])) {
                $this->sql->bindParam(':number', $record['number']);
            } else {
                $this->sql->bindParam(':number', $var = NULL);
            }
		*/	
            if (!empty($record['firstName'])) {
                $this->sql->bindParam(':firstName', $record['firstName']);
            } else {
                $this->sql->bindParam(':firstName', $var = NULL);
            }
/*
            if (!empty($record['lastName'])) {
                $this->sql->bindParam(':lastName', $record['lastName']);
            } else {
                $this->sql->bindParam(':lastName', $var = NULL);
            }

            if (!empty($record['patronymic'])) {
                $this->sql->bindParam(':patronymic', $record['patronymic']);
            } else {
                $this->sql->bindParam(':patronymic', $var = NULL);
            }

           if (!empty($record['email'])) {
                $this->sql->bindParam(':email', $record['email']);
            } else {
                $this->sql->bindParam(':email', $var = NULL);
            }

            if (!empty($record['phone'])) {
                $this->sql->bindParam(':phone', $record['phone']);
            } else {
                $this->sql->bindParam(':phone', $var = NULL);
            }

            if (!empty($record['customerComment'])) {
                $this->sql->bindParam(':description', $record['customerComment']);
            } else {
                $this->sql->bindParam(':description', $var = NULL);
            }

            if (!empty($record['delivery']['service']['code'])) {
                $this->sql->bindParam(':deliveryType', $record['delivery']['service']['code']);
            } else {
                $this->sql->bindParam(':deliveryType', $var = NULL);
            }

            if (!empty($record['delivery']['cost'])) {
                $this->sql->bindParam(':deliveryCost', $record['delivery']['cost']);
            } else {
                $this->sql->bindParam(':deliveryCost', $var = NULL);
            }

            if (!empty($record['paymentType'])) {
                $this->sql->bindParam(':paymentType', $record['paymentType']);
            } else {
                $this->sql->bindParam(':paymentType', $var = NULL);
            }
*/
            if (!empty($record['paymentStatus']) && $record['paymentStatus'] == 'paid') {
                $this->sql->bindParam(':paymentStatus', $status = 1);
            } else {
                $this->sql->bindParam(':paymentStatus', $status = 0);
            }
/*
            if (!empty($record['delivery']['address']['index'])) {
                $this->sql->bindParam(':postcode', $record['delivery']['address']['index']);
            } else {
                $this->sql->bindParam(':postcode', $var = NULL);
            }

            if (!empty($record['delivery']['address']['text'])) {
                $this->sql->bindParam(':address', $record['delivery']['address']['text']);
            } else {
                $this->sql->bindParam(':address', $var = NULL);
            }

            if (!empty($record['status']) && in_array($record['status'], $isCanceled)) {
                $this->sql->bindParam(':isCanceled', $cancel = 1);
            } else {
                $this->sql->bindParam(':isCanceled', $cancel = 0);
            }
*/
            try {
                $this->sql->execute();
                $this->oid =  $this->container->db->lastInsertId();
                if (empty($record['externalId'])) {
                    $response = $this->api->ordersFixExternalIds(
                        array(
                            array(
                                'id' => (int) $record['id'],
                                'externalId' => $this->oid
                            )
                        )
                    );
                }
            } catch (PDOException $e) {
                $this->logger->write(
                    'PDO: ' . $e->getMessage()//,
                   // $this->container->errorLog
                );
                return false;
            }

            if (!empty($record['items']) && empty($record['externalId'])) {

                foreach($record['items'] as $item) {
                    $items = $this->rule->getSQL('orders_history_create_items');
                    $this->query = $this->container->db->prepare($items);
                    $this->query->bindParam(':id', $this->oid);
                    $this->query->bindParam(':variant_id', $item['offer']['externalId']);
                    $this->query->bindParam(':name', $item['offer']['name']);
                    $this->query->bindParam(':amount', $item['quantity']);
                    $this->query->bindParam(':price', $item['initialPrice']);

                    try {
                        $this->query->execute();
                    } catch (PDOException $e) {
                        $this->logger->write(
                            'PDO: ' . $e->getMessage()//,
                           // $this->container->errorLog
                        );
                        return false;
                    }
                }
            }
        }
    }
}