ALTER TABLE `node-points`.`students`
ADD COLUMN `openid` varchar(50) NULL COMMENT '微信用户id' AFTER `wallet_address`,
ADD UNIQUE INDEX `openid_key`(`openid`(10)) USING HASH;

ALTER TABLE `node-points`.`students`
ADD COLUMN `profile` varchar(255) NULL COMMENT '头像URL' AFTER `openid`;

ALTER TABLE `node-points`.`students`
ADD COLUMN `comments` varchar(255) NULL COMMENT '备注，可以存放其他信息' AFTER `profile`;

