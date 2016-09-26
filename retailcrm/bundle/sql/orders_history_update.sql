UPDATE
    `s_orders`
SET 
	`name`=:firstName,
	`paid`=:paymentStatus
    
 WHERE id=:externalId;