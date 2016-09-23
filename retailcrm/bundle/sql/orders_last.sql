SELECT
    orders.id AS externalId,
    (
        CASE
            orders.user_id
        WHEN
            0
        THEN
            NULL
        ELSE
            orders.user_id
        END
    ) AS customerId,
    orders.name AS firstName,
    /*orders.name AS lastName,
    orders.name AS patronymic,*/
    orders.email AS email,
    orders.phone AS phone,
    (
        CASE
            orders.delivery_id
        WHEN
            0
        THEN
            NULL
        ELSE
            orders.delivery_id
        END
    ) AS deliveryIndex,
    'Россия' AS deliveryCountry,
    'Центр' AS deliveryRegion,
    'Липецк' AS deliveryCity,
    orders.address AS deliveryAddress,
    orders.delivery_id AS deliveryType,
    orders.delivery_id AS deliveryService,
    orders.payment_method_id AS paymentType,
    (
        CASE
            orders.paid
        WHEN
            1
        THEN
            'paid'
        WHEN
            0
        THEN
            'not-paid'
        ELSE
            'more'
        END
    ) AS paymentStatus,
    orders.comment AS customerComment,
    orders.date AS createdAt,
    (
        SELECT
            GROUP_CONCAT(
                CONCAT_WS(
                    ';',
                    variant_id,
                    CONCAT_WS(' ', product_name,variant_name),
                    amount,
                    price
                )
                SEPARATOR '|'
            )
        FROM
            s_purchases
        WHERE
            product_id != 0
        AND
            order_id = orders.id
        GROUP BY
            order_id
    ) AS items
FROM
    s_orders AS orders

	
LEFT JOIN
    s_payment_methods AS payment
ON
    (payment.id = orders.payment_method_id)
	
	
LEFT JOIN
    s_delivery AS delivery
ON
    (delivery.id = orders.delivery_id)
	
WHERE
    orders.id > 0
AND
    orders.modified BETWEEN :lastSync AND NOW()
ORDER BY
    orders.id ASC