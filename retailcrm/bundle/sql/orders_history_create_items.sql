INSERT INTO
    `s_purchases`
(
    `order_id`,
    `variant_id`,
    `product_name`,
	`variant_name`,
    `amount`,
    `price`,
	`sku`
)

VALUES

(
	:order_id,
    :variant_id,
    :product_name,
	:variant_name,
    :amount,
    :price,
	:sku
)