ALTER TABLE `node`.`students` ADD COLUMN `wechat_unionId` varchar(64) NOT NULL AFTER `updated_at`, ADD UNIQUE `unionid` USING HASH (`wechat_unionId`) comment '';
