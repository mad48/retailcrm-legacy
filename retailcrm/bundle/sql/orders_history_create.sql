INSERT IGNORE INTO
    `s_orders`
(
    `number`,
	`name`,
    /*`name`,
    `name`,*/
    `email`,
    `phone`,
    `delivery_id`,
    `delivery_price`,
    `payment_method_id`,
    `paid`,
    `payment_date`,
    `address`,
    /*`shop_order_index`,*/
    `comment`,
    `date`,
    `closed`,
    /*`shop_country_id`,
    `shop_shops_id`,*/
    `user_id`
)

SELECT
    IF(:number IS NOT NULL, :number, ''),
	IF(:firstName IS NOT NULL, :firstName, ''),
    /*IF(:lastName IS NOT NULL, :lastName, ''),
    IF(:patronymic IS NOT NULL, :patronymic, ''),*/
    IF(:email IS NOT NULL, :email, ''),
    IF(:phone IS NOT NULL, :phone, ''),
    IF(:deliveryType IS NOT NULL, :deliveryType, ''),
    IF(:deliveryCost IS NOT NULL, :deliveryCost, ''),
    IF(:paymentType IS NOT NULL, :paymentType, ''),
    IF(:paymentStatus IS NOT NULL, :paymentStatus, 0),
    IF(:paymentStatus = 1, NOW(), '0000-00-00 00:00:00'),
    IF(:address IS NOT NULL, :address, ''),
    /*IF(:postcode IS NOT NULL, :postcode, 0),*/
    IF(:description IS NOT NULL, :description, ''),
    IF(:createdAt IS NOT NULL, :createdAt, NOW()),
    :isCanceled,
   /* 175,
    1,*/
    (MAX(id) + 1)
FROM
    `s_orders`