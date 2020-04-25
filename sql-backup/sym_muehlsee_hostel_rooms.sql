create table hostel_rooms
(
    id          int auto_increment
        primary key,
    hostel_id   int not null,
    hostel_room int not null
)
    collate = utf8mb4_unicode_ci;

