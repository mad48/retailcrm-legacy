UPDATE
    `s_purchases`
SET
    `order_id`=:order_id,
    `variant_id`=:variant_id,
    `product_name`=:product_name,
	`variant_name`=:variant_name,
    `amount`=:amount,
    `price`=:price,
	`sku`=:sku
WHERE 
	order_id=:order_id AND
	variant_id=:variant_id
