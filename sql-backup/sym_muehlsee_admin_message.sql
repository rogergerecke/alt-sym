create table admin_message
(
    id          int auto_increment
        primary key,
    heading     varchar(255) not null,
    sub_heading varchar(255) null,
    message     longtext     null,
    type        varchar(255) not null,
    date_add    datetime     not null
)
    collate = utf8mb4_unicode_ci;

INSERT INTO sym_muehlsee.admin_message (id, heading, sub_heading, message, type, date_add) VALUES (1, 'Json Validation Error beim Import der Wetterdaten', null, 'Error String vom Validator ', 'danger', '2020-04-17 22:50:23');