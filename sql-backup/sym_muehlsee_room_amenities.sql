create table room_amenities
(
    id            int auto_increment
        primary key,
    name          varchar(255) not null,
    type          varchar(10)  not null,
    default_value varchar(10)  not null,
    sort          smallint     not null,
    status        tinyint(1)   not null
)
    collate = utf8mb4_unicode_ci;

INSERT INTO sym_muehlsee.room_amenities (id, name, type, default_value, sort, status) VALUES (1, 'distance_to_see', 'float', '10', 1, 1);
INSERT INTO sym_muehlsee.room_amenities (id, name, type, default_value, sort, status) VALUES (2, 'family_friendly', 'boolean', 'false', 0, 1);
INSERT INTO sym_muehlsee.room_amenities (id, name, type, default_value, sort, status) VALUES (3, 'handicapped_accessible', 'boolean', 'false', 0, 1);
INSERT INTO sym_muehlsee.room_amenities (id, name, type, default_value, sort, status) VALUES (4, 'animal_friendly', 'boolean', 'false', 0, 1);
INSERT INTO sym_muehlsee.room_amenities (id, name, type, default_value, sort, status) VALUES (5, 'lawn', 'boolean', 'false', 0, 1);
INSERT INTO sym_muehlsee.room_amenities (id, name, type, default_value, sort, status) VALUES (6, 'quiet_location', 'boolean', 'false', 0, 1);
INSERT INTO sym_muehlsee.room_amenities (id, name, type, default_value, sort, status) VALUES (7, 'parking', 'boolean', 'true', 1, 1);
INSERT INTO sym_muehlsee.room_amenities (id, name, type, default_value, sort, status) VALUES (8, 'bicycle', 'boolean', 'false', 0, 1);
INSERT INTO sym_muehlsee.room_amenities (id, name, type, default_value, sort, status) VALUES (9, 'childrens_playground', 'boolean', 'false', 0, 1);
INSERT INTO sym_muehlsee.room_amenities (id, name, type, default_value, sort, status) VALUES (10, 'sauna', 'boolean', 'false', 0, 1);
INSERT INTO sym_muehlsee.room_amenities (id, name, type, default_value, sort, status) VALUES (11, 'fitness_room', 'boolean', 'false', 0, 1);
INSERT INTO sym_muehlsee.room_amenities (id, name, type, default_value, sort, status) VALUES (12, 'lounge', 'boolean', 'false', 0, 1);
INSERT INTO sym_muehlsee.room_amenities (id, name, type, default_value, sort, status) VALUES (13, 'barbecue', 'boolean', 'false', 0, 1);
INSERT INTO sym_muehlsee.room_amenities (id, name, type, default_value, sort, status) VALUES (14, 'bread_service', 'boolean', 'false', 0, 1);
INSERT INTO sym_muehlsee.room_amenities (id, name, type, default_value, sort, status) VALUES (15, 'breakfast', 'boolean', 'false', 0, 1);
INSERT INTO sym_muehlsee.room_amenities (id, name, type, default_value, sort, status) VALUES (16, 'half_board', 'boolean', 'false', 0, 1);
INSERT INTO sym_muehlsee.room_amenities (id, name, type, default_value, sort, status) VALUES (17, 'single_night', 'boolean', 'true', 1, 1);
INSERT INTO sym_muehlsee.room_amenities (id, name, type, default_value, sort, status) VALUES (18, 'Washing machine', 'boolean', 'false', 0, 1);
INSERT INTO sym_muehlsee.room_amenities (id, name, type, default_value, sort, status) VALUES (19, 'Dishwasher', 'boolean', 'false', 0, 1);
INSERT INTO sym_muehlsee.room_amenities (id, name, type, default_value, sort, status) VALUES (20, 'fishing', 'boolean', 'false', 0, 1);
INSERT INTO sym_muehlsee.room_amenities (id, name, type, default_value, sort, status) VALUES (21, 'indoor_pool', 'boolean', 'false', 0, 1);
INSERT INTO sym_muehlsee.room_amenities (id, name, type, default_value, sort, status) VALUES (22, 'non_smoking', 'boolean', 'true', 1, 1);
INSERT INTO sym_muehlsee.room_amenities (id, name, type, default_value, sort, status) VALUES (23, 'bike_rental', 'boolean', 'false', 0, 1);
INSERT INTO sym_muehlsee.room_amenities (id, name, type, default_value, sort, status) VALUES (24, 'wlan', 'boolean', 'false', 1, 1);