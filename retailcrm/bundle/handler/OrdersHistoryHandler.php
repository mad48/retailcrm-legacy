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

        /*$orderGroups = $this->api->statusGroupsList();

        if (!is_null($orderGroups)) {
            $isCanceled = $orderGroups['statusGroups']['cancel']['statuses'];
        }*/

        $create = $this->rule->getSQL('orders_history_create');

        foreach ($data as $record) {

            if (!empty($record['deleted'])) continue;

            echo "OrdersHistoryHandler";
            print_r($record);

            if (empty($record['externalId'])) {

                $this->sql = $this->container->db->prepare($create);

                if (!empty($record['createdAt'])) {
                    $this->sql->bindParam(':createdAt', $record['createdAt']);
                } else {
                    $this->sql->bindParam(':createdAt', $var = NULL);
                }

                if (!empty($record['number'])) {
                    $this->sql->bindParam(':number', $record['number']);
                } else {
                    $this->sql->bindParam(':number', $var = NULL);
                }

                if (!empty($record['summ'])) {
                    $this->sql->bindParam(':summ', $record['summ']);
                } else {
                    $this->sql->bindParam(':summ', $var = NULL);
                }

                if (!empty($record['totalSumm'])) {
                    $this->sql->bindParam(':totalSumm', $record['totalSumm']);
                } else {
                    $this->sql->bindParam(':totalSumm', $var = NULL);
                }

                if (!empty($record['discount'])) {
                    $this->sql->bindParam(':discount', $record['discount']);
                } else {
                    $this->sql->bindParam(':discount', $var = NULL);
                }

                if (!empty($record['purchaseSumm'])) {
                    $this->sql->bindParam(':purchaseSumm', $record['purchaseSumm']);
                } else {
                    $this->sql->bindParam(':purchaseSumm', $var = NULL);
                }

                if (!empty($record['firstName'])) {
                    $this->sql->bindParam(':firstName', $record['firstName']);
                } else {
                    $this->sql->bindParam(':firstName', $var = NULL);
                }

                if (!empty($record['customerComment'])) {
                    $this->sql->bindParam(':customerComment', $record['customerComment']);
                } else {
                    $this->sql->bindParam(':customerComment', $var = NULL);
                }

                if (!empty($record['managerComment'])) {
                    $this->sql->bindParam(':managerComment', $record['managerComment']);
                } else {
                    $this->sql->bindParam(':managerComment', $var = NULL);
                }

                if (!empty($record['paymentType'])) {
                    $this->sql->bindParam(':paymentType', $record['paymentType']);
                } else {
                    $this->sql->bindParam(':paymentType', $var = NULL);
                }

                if (!empty($record['paymentStatus']) && $record['paymentStatus'] == 'paid') {
                    $this->sql->bindParam(':paymentStatus', $status = 1);
                } else {
                    $this->sql->bindParam(':paymentStatus', $status = 0);
                }

                if (!empty($record['customer']['id'])) {
                    $this->sql->bindParam(':customerId', $record['customer']['id']);
                } else {
                    $this->sql->bindParam(':customerId', $var = NULL);
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

                if (!empty($record['delivery']['cost'])) {
                    $this->sql->bindParam(':deliverycost', $record['delivery']['cost']);
                } else {
                    $this->sql->bindParam(':deliverycost', $var = NULL);
                }

                if (!empty($record['delivery']['code'])) {
                    $this->sql->bindParam(':postcode', $record['delivery']['code']);
                } else {
                    $this->sql->bindParam(':postcode', $var = NULL);
                }

                if (!empty($record['delivery']['address']['text'])) {
                    $this->sql->bindParam(':address', $record['delivery']['address']['text']);
                } else {
                    $this->sql->bindParam(':address', $var = NULL);
                }

            } else {


                $tmp_sql[] = "modified=NOW()";


                if (!empty($record['delivery']['code'])) {
                    $tmp_sql[] = 'delivery_id=:delivery_id';
                }

                if (!empty($record['delivery']['cost'])) {
                    $tmp_sql[] = 'delivery_price=:delivery_price';
                }

                if (!empty($record['paymentType'])) {
                    $tmp_sql[] = 'payment_method_id=:payment_method_id';
                }

                if (!empty($record['paymentStatus']) && $record['paymentStatus'] == 'paid') {
                    $tmp_sql[] = 'paid=:paid';
                }

                if (!empty($record['paymentStatus']) && $record['paymentStatus'] == 'not-paid') {
                    $tmp_sql[] = 'paid=:paid';
                }

                if (!empty($record['createdAt'])) {
                    $tmp_sql[] = 'date=:date';
                }

                if (!empty($record['customer']['id'])) {
                    $tmp_sql[] = 'user_id=:user_id';
                }

                if (!empty($record['firstName'])) {
                    $tmp_sql[] = 'name=:name';
                }

                if (!empty($record['delivery']['address']['text'])) {
                    $tmp_sql[] = 'address=:address';
                } else {
                    $delivery_addr = array("region", "city", "metro", "street", "house", "building", "block", "flat", "floor", "intercomCode");
                    foreach ($delivery_addr as $item_addr) {
                        if (isset($record["delivery"]["address"][$item_addr])) {
                            $full_addr[] = $record["delivery"]["address"][$item_addr];
                        }
                    }
                    if (!empty($full_addr)) {
                        $tmp_sql[] = 'address=:address';
                    }
                }

                if (!empty($record['phone'])) {
                    $tmp_sql[] = 'phone=:phone';
                }

                if (!empty($record['email'])) {
                    $tmp_sql[] = 'email=:email';
                }

                if (!empty($record['customerComment'])) {
                    $tmp_sql[] = 'comment=:comment';
                }

                if (!empty($record['summ'])) {
                    $tmp_sql[] = 'total_price=:total_price';
                }

                if (!empty($record['managerComment'])) {
                    $tmp_sql[] = 'note=:note';
                }

                if (!empty($record['discount'])) {
                    $tmp_sql[] = 'discount=:discount';
                }


                $this->sql = $this->container->db->prepare('UPDATE `s_orders` SET ' . implode(", ", $tmp_sql) . ' WHERE id=:externalId');

                $this->sql->bindParam(':externalId', $record['externalId']);


                if (!empty($record['delivery']['code'])) {
                    $this->sql->bindParam(':delivery_id', $record['delivery']['code']);
                }

                if (!empty($record['delivery']['cost'])) {
                    $this->sql->bindParam(':delivery_price', $record['delivery']['cost']);
                }

                if (!empty($record['paymentType'])) {
                    $this->sql->bindParam(':payment_method_id', $record['paymentType']);
                }

                if (!empty($record['paymentStatus']) && $record['paymentStatus'] == 'paid') {
                    $this->sql->bindParam(':paid', 1);
                }

                if (!empty($record['paymentStatus']) && $record['paymentStatus'] == 'not-paid') {
                    $this->sql->bindParam(':paid', 0);
                }

                if (!empty($record['createdAt'])) {
                    $this->sql->bindParam(':date', $record['createdAt']);
                }

                if (!empty($record['customer']['id'])) {
                    $this->sql->bindParam(':user_id', $record['customer']['id']);
                }

                if (!empty($record['firstName'])) {
                    $this->sql->bindParam(':name', $record['firstName']);
                }

                if (!empty($record['delivery']['address']['text'])) {
                    $this->sql->bindParam(':address', $record['delivery']['address']['text']);
                } else {

                    if (!empty($full_addr)) {
                        $this->sql->bindParam(':address', implode(", ", $full_addr));
                    }
                }

                if (!empty($record['phone'])) {
                    $this->sql->bindParam(':phone', $record['phone']);
                }

                if (!empty($record['email'])) {
                    $this->sql->bindParam(':email', $record['email']);
                }

                if (!empty($record['customerComment'])) {
                    $this->sql->bindParam(':comment', $record['customerComment']);
                }

                if (!empty($record['summ'])) {
                    $this->sql->bindParam(':total_price', $record['summ']);
                }

                if (!empty($record['managerComment'])) {
                    $this->sql->bindParam(':note', $record['managerComment']);
                }

                if (!empty($record['discount'])) {
                    $this->sql->bindParam(':discount', $record['discount']);
                }


            }

            try {
                $this->sql->execute();
                $this->oid = $this->container->db->lastInsertId();
                if (empty($record['externalId'])) {
                    $response = $this->api->ordersFixExternalIds(
                        array(
                            array(
                                'id' => (int)$record['id'],
                                'externalId' => $this->oid
                            )
                        )
                    );
                }
            } catch (PDOException $e) {
                $this->logger->write(
                    'PDO: ' . $e->getMessage(),
                    $this->container->errorLog
                );
                return false;
            }

            if (!empty($record['items'])) {

                foreach ($record['items'] as $item) {

                    if (!empty($item['deleted'])) {
                        $this->query = $this->container->db->prepare(
                            'DELETE FROM `s_purchases` WHERE order_id=:order_id AND variant_id=:variant_id'
                        );
                        $this->query->bindParam(':order_id', $record['externalId']);
                        $this->query->bindParam(':variant_id', $item['id']);
                        $this->query->execute();
                        continue;
                    }

                    if (!empty($item['created'])) {
                        $items = $this->rule->getSQL('orders_history_create_items');
                    } else
                        $items = $this->rule->getSQL('orders_history_update_items');

                    $this->query = $this->container->db->prepare($items);

                    $this->query->bindParam(':order_id', empty($record['externalId']) ? $record['id'] : $record['externalId']);
                    $this->query->bindParam(':price', $item['initialPrice']);
                    $this->query->bindParam(':amount', $item['quantity']);
                    $this->query->bindParam(':variant_id', $item['offer']['externalId']);
                    $this->query->bindParam(':product_name', $item['offer']['name']);
                    $this->query->bindParam(':variant_name', $item['offer']['name']);
                    $this->query->bindParam(':sku', $item['offer']['name']);

                    try {
                        $this->query->execute();
                    } catch (PDOException $e) {
                        $this->logger->write(
                            'PDO: ' . $e->getMessage(),
                            $this->container->errorLog
                        );
                        return false;
                    }
                }
            }
        }
    }
}