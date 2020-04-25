create table amenities_types
(
    id           int auto_increment
        primary key,
    amenities_id int          not null,
    name         varchar(255) not null,
    status       tinyint(1)   not null,
    sort         smallint     not null
)
    collate = utf8mb4_unicode_ci;

INSERT INTO sym_muehlsee.amenities_types (id, amenities_id, name, status, sort) VALUES (1, 1, 'Hotel', 1, 3);
INSERT INTO sym_muehlsee.amenities_types (id, amenities_id, name, status, sort) VALUES (2, 2, 'Gasthof', 1, 4);
INSERT INTO sym_muehlsee.amenities_types (id, amenities_id, name, status, sort) VALUES (3, 3, 'Pension', 1, 2);
INSERT INTO sym_muehlsee.amenities_types (id, amenities_id, name, status, sort) VALUES (4, 4, 'Ferienhaus', 1, 1);
INSERT INTO sym_muehlsee.amenities_types (id, amenities_id, name, status, sort) VALUES (5, 5, 'Ferienwohnung', 1, 5);
INSERT INTO sym_muehlsee.amenities_types (id, amenities_id, name, status, sort) VALUES (6, 7, 'Camping', 1, 6);
INSERT INTO sym_muehlsee.amenities_types (id, amenities_id, name, status, sort) VALUES (7, 8, 'Appartement', 1, 7);
INSERT INTO sym_muehlsee.amenities_types (id, amenities_id, name, status, sort) VALUES (8, 10, 'Restaurant', 1, 8);
INSERT INTO sym_muehlsee.amenities_types (id, amenities_id, name, status, sort) VALUES (9, 11, 'Bauernhof', 1, 9);
INSERT INTO sym_muehlsee.amenities_types (id, amenities_id, name, status, sort) VALUES (10, 17, 'Gästehaus', 1, 10);
INSERT INTO sym_muehlsee.amenities_types (id, amenities_id, name, status, sort) VALUES (11, 18, 'Ferienzimmer', 1, 11);