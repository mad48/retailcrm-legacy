SELECT
    `id` as `code`,
    `name` as `name`,
	`enabled` as `active`,
	`price` as `defaultCost`,
	`description` as `description`,
    `id` as `deliveryType`
FROM
    `s_delivery`

ORDER BY
    `id` ASC