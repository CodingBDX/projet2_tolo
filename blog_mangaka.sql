-- Active: 1660475214465@@symfony-mysql-1@3306@symfony6
CREATE DATABASE jean;
use  jean;

create table articles
(
    id      int auto_increment
        primary key,
    title   varchar(255) null,
    content text         null,
    author  varchar(255) null,
    picture text         null,
    date    varchar(80)  null
);

create table articles_categories
(
    articles_id   int not null,
    categories_id int not null
);

create table categories
(
    id   int auto_increment
        primary key,
    name varchar(100) null
);

create table users
(
    id       int auto_increment
        primary key,
    name     varchar(100) not null,
    password varchar(100) not null,
    mail     varchar(255) not null,
    isAdmin  tinyint(1)   not null,


);
ALTER TABLE users
ADD COLUMN `date` DATE  DEFAULT GETDATE();

ALTER TABLE users
ADD date DATETIME;

INSERT INTO users (`name`, `password`, `mail`, `isAdmin`) VALUES ('admin','admin','der1@defr.fr',true);
create table comments
(
    id          int auto_increment
        primary key,
    content     text null,
    users_id    int  not null,
    articles_id int  not null,
    constraint comments_articles
        foreign key (articles_id) references articles (id),
    constraint comments_users
        foreign key (users_id) references users (id)
);


