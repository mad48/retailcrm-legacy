SELECT
    `id` as `code`,
    `name` as `name`,
    `description` as `description`,
	`enabled` as `active`
FROM
    `s_payment_methods`
WHERE
    `id` > 0