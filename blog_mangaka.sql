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

select * from articles;
SELECT now();
SELECT title AS supertitle,count(content) AS supercontent
FROM articles
GROUP BY id,content
HAVING id>3
ORDER BY title DESC;


DELETE FROM articles
where id='2';
INSERT INTO articles (`title`, `content`, `author`, `picture`) VALUES ('nicki larson lover','un super content qui depasse largement les 100 characteres, est ce que cela va s arreter au bonne endroit, mystere et boulle de chewingum ou l expression que vous voulez','jean le roi','https://cdn-elle.ladmedia.fr/var/plain_site/storage/images/loisirs/series/nicky-larson-albator-cobra-les-fantasmes-mangas-secrets-de-notre-enfance-4019152/96621935-1-fre-FR/Nicky-Larson-Albator-Cobra-les-fantasmes-mangas-secrets-de-notre-enfance.jpg');

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
ADD COLUMN picture TEXT DEFAULT 'assets/img/default.png';

ALTER TABLE users
ADD date DATETIME;

INSERT INTO users (`id`,`name`, `password`, `mail`, `isAdmin`) VALUES (1,'admin','admin','der1@defr.fr',true);
create table comments_manga
(
    id          int auto_increment
        primary key,
    content     text null,
    users_id    int  not null,
    mangas_malid int  not null,
    constraint comments_mangas
        foreign key (mangas_malid) references mangas (id),
    constraint comments_users
        foreign key (users_id) references users (id)
);
create table mangas
(id          int auto_increment
        primary key,
    id_malid int not null
);

create table animes
(id          int auto_increment
        primary key,
    id_malid int not null
);

create table comments_anime
(
    id          int auto_increment
        primary key,
    content     text null,
    users_id    int  not null,
    animes_malid int  not null,
    constraint comments_animes
        foreign key (animes_malid) references animes (id),
    constraint comments_users
        foreign key (users_id) references users (id)
);

create table comments_article
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
ALTER TABLE comments
MODIFY COLUMN articles_id int null;







