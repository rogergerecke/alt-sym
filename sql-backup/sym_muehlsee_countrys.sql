create table countrys
(
    id         int auto_increment
        primary key,
    country_id int          not null,
    name       varchar(255) not null,
    status     tinyint(1)   not null
)
    collate = utf8mb4_unicode_ci;

