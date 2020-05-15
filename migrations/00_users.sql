CREATE TABLE `users` (
`id` int(10) NOT NULL auto_increment,
`email` varchar(32)  NOT NULL default '',
`username` varchar(20)  NOT NULL default '',
`password` varchar(32)  NOT NULL default '',
`is_admin`  int(1) not null default 0 ,
`author_id`  int(10) not null default 0 ,
PRIMARY KEY  (`id`),
UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



