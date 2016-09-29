INSERT IGNORE INTO
    `s_orders`
(
    `id`,
	`name`,
    `email`,
    `phone`,
    `address`,
	`delivery_id`,
	`delivery_price`,
    `comment`,
	`note`,
	`total_price`,
	`discount`,
	`payment_method_id`,
	`paid`,
    `date`,
    `user_id`
)

SELECT
    IF(:number IS NOT NULL, :number, ''),
	IF(:firstName IS NOT NULL, :firstName, ''),
    IF(:email IS NOT NULL, :email, ''),
    IF(:phone IS NOT NULL, :phone, ''),
	IF(:address IS NOT NULL, :address, ''),
	IF(:postcode IS NOT NULL, :postcode, ''),
	IF(:deliverycost IS NOT NULL, :deliverycost, ''),
    IF(:customerComment IS NOT NULL, :customerComment, ''),
	IF(:managerComment IS NOT NULL, :managerComment, ''),
	IF(:totalSumm IS NOT NULL, :totalSumm, 0),
	IF(:discount IS NOT NULL, :discount, 0),
	IF(:paymentType IS NOT NULL, :paymentType, ''),
	IF(:paymentStatus IS NOT NULL, :paymentStatus, 0),
    IF(:createdAt IS NOT NULL, :createdAt, NOW()),
	IF(:customerId IS NOT NULL, :customerId, 0)
FROM
    `s_orders`
