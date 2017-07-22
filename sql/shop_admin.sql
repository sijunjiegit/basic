/*管理员表*/
create table if not exists `shop_admin`(
    `adminid` int unsigned not null auto_increment,
    `adminuser` varchar(32) not null default '',
    `adminpass` char(32) not null default '',
    `adminemail` varchar(50) not null default '',
    `logintime` int unsigned not null default 0,
    `loginip` bigint not null default 0,
    `createtime` int unsigned not null default 0,
    primary key (`adminid`),
    unique shop_admin_adminuser_adminpass(`adminuser`, `adminpass`),
    unique show_admin_adminuser_admineamil(`adminuser`, `adminemail`)
    )engine=innodb default charset=utf8;

insert into 
	`shop_admin`(`adminuser`, `adminpass`, `adminemail`, `logintime`, `createtime`) 
	values('sijunjie', md5('Sijunjie521'), '980937631@qq.com', unix_timestamp(now()), unix_timestamp(now()));

/*用户表*/
create table if exists `shop_user`;
create table if not exists `shop_user`(
    `userid` bigint unsigned not null auto_increment,
    `username` varchar(32) not null default '',
    `userpass` char(32) not null default '',
    `useremail` varchar(100) not null default '',
    `createtime` int unsigned not null default 0,
    unique show_user_username_userpass(`username`, `userpass`),
    unique show_user_useremail_userpass(`useremail`, `userpass`),
    primary key(`userid`)
)engine=innodb default charset=utf8;

/*用户关联表*/
drop table if exists `show_profile`;
create table if not exists `shop_profile`(
    `id` bigint unsigned not null auto_increment,
    `truename` varchar(32) not null default '',
    `age` tinyint unsigned not null default 0,
    `sex` enum('0', '1', '2') not null default '0',
    `brithday` date not null default '2016-01-01',
    `nickname` varchar(32) not null default '',
    `company` varchar(100) not null default '',
    `userid` bigint unsigned not null default 0,
    `createtime` int unsigned not null default 0,
    primary key(`id`),
    unique `shop_profile_userid`(`userid`)
)engine=innodb default charset=utf8;

/*商品分类表*/
drop table if exists `shop_category`;
create table if not exists `shop_category`(
    `cateid` bigint unsigned not null auto_increment,
    `title` varchar(32) not null default '',
    `parentid` bigint unsigned not null default 0,
    `createtime` int unsigned not null default 0,
    primary key (`cateid`),
    key `shop_category_parentid`(`parentid`)
)engine=innodb default charset=utf8;
)
