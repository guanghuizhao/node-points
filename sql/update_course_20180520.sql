-- 更改上课时间为开始时间和结束时间两个字段
ALTER TABLE `node-points`.`courses`
CHANGE COLUMN `time` `start_time` datetime(0) NOT NULL COMMENT '上课开始时间' AFTER `teather`,
ADD COLUMN `end_time` datetime(0) NOT NULL COMMENT '上课结束时间' AFTER `start_time`