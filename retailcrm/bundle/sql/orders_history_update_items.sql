UPDATE
    `s_purchases`
SET
    `order_id`=:order_id,
    `variant_id`=:variant_id,
    `product_name`=:product_name,
	`variant_name`=:variant_name,
    `amount`=:amount,
    `price`=:price,
	`sku`=:sku,
	`product_id`=(SELECT s_variants.product_id FROM `s_variants` WHERE s_variants.id=:variant_id)
WHERE 
	order_id=:order_id AND
	variant_id=:variant_id
