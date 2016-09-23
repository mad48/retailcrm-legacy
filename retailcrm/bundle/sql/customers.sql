SELECT
	customers.id as `id`,
	customers.name as `firstName`,
	`email` as `email`,
	`created` as `createdAt`,
	 (
        CASE
            group_id
        WHEN
            0
        THEN
            0
        ELSE
            1
        END
    ) AS `vip`,
	
	(
        CASE
            groups.id
        WHEN
            0
        THEN
            0
        ELSE
            groups.discount
        END
    ) AS `personalDiscount`	
	
	
FROM
    `s_users` as customers
LEFT JOIN
    s_groups AS groups
ON
    (groups.id = group_id)
WHERE
    `enabled` = 1
ORDER BY
    `id`