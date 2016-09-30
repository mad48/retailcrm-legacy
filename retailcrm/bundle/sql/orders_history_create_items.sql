INSERT INTO
    `s_purchases`
(
    `order_id`,
    `variant_id`,
    `product_name`,
	`variant_name`,
    `amount`,
    `price`,
	`sku`,
	`product_id`
)

VALUES

(
	:order_id,
    :variant_id,
    :product_name,
	:variant_name,
    :amount,
    :price,
	:sku,
	(SELECT s_variants.product_id FROM `s_variants` WHERE s_variants.id=:variant_id)
)