CREATE TABLE IF NOT EXISTS `#__steamid` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`steamid` VARCHAR(255)  NOT NULL ,
`user_id` INT NOT NULL ,
`personaname` VARCHAR(255)  NOT NULL ,
`realname` VARCHAR(255)  NOT NULL ,
`profileurl` VARCHAR(255)  NOT NULL ,
`avatar` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

