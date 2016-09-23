SELECT
    variants.id as id,
    variants.product_id as productId,
	'10' as quantity,
	variants.id as article,
    groups.category_id as categoryId,
    products.name as name,
    variants.price as price,
	variants.name as color,
	brands.name as vendor,    
    products.url as url,
    /*products.annotation as description,*/
	
	(SELECT 
	        GROUP_CONCAT(
                CONCAT_WS(
                    ';',
                    filename
                )
                SEPARATOR '|'
            ) 
	FROM s_images WHERE product_id = productId ORDER BY position) as picture

			
FROM
    s_variants as variants

	
LEFT JOIN
    s_products as products
ON
    (variants.product_id = products.id)
	
	
	
LEFT JOIN
    s_products_categories as groups
ON
    (groups.product_id = products.id)
	

LEFT JOIN
    s_brands as brands
ON
    (brands.id = products.brand_id)
	
	
WHERE
    products.visible != ''
AND
    products.id >= 1
ORDER BY
    categoryId