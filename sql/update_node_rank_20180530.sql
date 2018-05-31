CREATE TABLE `node-points`.`无标题`  (
  `id` int(0) NOT NULL,
  `student_id` int(0) NOT NULL,
  `total` double NOT NULL,
  `created_at` datetime(0) NOT NULL,
  `updated_at` datetime(0) NOT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`),
  UNIQUE INDEX `student_id`(`student_id`),
  INDEX `total`(`total`)
);
