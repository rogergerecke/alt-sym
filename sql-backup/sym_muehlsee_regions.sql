create table regions
(
    id         int auto_increment
        primary key,
    name       varchar(255) not null,
    zipcode    int          not null,
    status     tinyint      not null,
    regions_id int          not null
)
    collate = utf8mb4_unicode_ci;

INSERT INTO sym_muehlsee.regions (id, name, zipcode, status, regions_id) VALUES (3, 'Gunzenhausen', 91710, 0, 5);
INSERT INTO sym_muehlsee.regions (id, name, zipcode, status, regions_id) VALUES (4, 'Streudorf', 91710, 0, 54);
INSERT INTO sym_muehlsee.regions (id, name, zipcode, status, regions_id) VALUES (5, 'Büchelberg', 91710, 0, 49);
INSERT INTO sym_muehlsee.regions (id, name, zipcode, status, regions_id) VALUES (6, 'Wald', 91710, 0, 51);
INSERT INTO sym_muehlsee.regions (id, name, zipcode, status, regions_id) VALUES (7, 'Unterwurmbach', 91710, 0, 55);
INSERT INTO sym_muehlsee.regions (id, name, zipcode, status, regions_id) VALUES (8, 'Schlungenhof', 91710, 0, 53);
INSERT INTO sym_muehlsee.regions (id, name, zipcode, status, regions_id) VALUES (9, 'Frickenfelden', 91710, 1, 58);
INSERT INTO sym_muehlsee.regions (id, name, zipcode, status, regions_id) VALUES (10, 'Laubenzedel', 91710, 1, 59);
INSERT INTO sym_muehlsee.regions (id, name, zipcode, status, regions_id) VALUES (11, 'Unterwurmbach', 91743, 1, 55);
INSERT INTO sym_muehlsee.regions (id, name, zipcode, status, regions_id) VALUES (12, 'Dittenheim', 91710, 1, 66);
INSERT INTO sym_muehlsee.regions (id, name, zipcode, status, regions_id) VALUES (13, 'Merkendorf', 91710, 1, 60);
INSERT INTO sym_muehlsee.regions (id, name, zipcode, status, regions_id) VALUES (14, 'Ornbau', 91710, 1, 57);
INSERT INTO sym_muehlsee.regions (id, name, zipcode, status, regions_id) VALUES (15, 'Arberg', 91710, 1, 56);
INSERT INTO sym_muehlsee.regions (id, name, zipcode, status, regions_id) VALUES (16, 'Gnotzheim', 91710, 1, 67);
INSERT INTO sym_muehlsee.regions (id, name, zipcode, status, regions_id) VALUES (17, 'Muhr am See', 91710, 1, 52);
INSERT INTO sym_muehlsee.regions (id, name, zipcode, status, regions_id) VALUES (18, 'Pfofeld', 91738, 1, 74);
INSERT INTO sym_muehlsee.regions (id, name, zipcode, status, regions_id) VALUES (19, 'Theilenhofen', 91741, 1, 75);
INSERT INTO sym_muehlsee.regions (id, name, zipcode, status, regions_id) VALUES (20, 'Haundorf', 91729, 1, 77);
INSERT INTO sym_muehlsee.regions (id, name, zipcode, status, regions_id) VALUES (21, 'Gräfensteinberg', 91729, 1, 78);
INSERT INTO sym_muehlsee.regions (id, name, zipcode, status, regions_id) VALUES (22, 'Wachstein', 91741, 1, 79);
INSERT INTO sym_muehlsee.regions (id, name, zipcode, status, regions_id) VALUES (23, 'Aha', 91710, 1, 61);
INSERT INTO sym_muehlsee.regions (id, name, zipcode, status, regions_id) VALUES (24, 'Heidenheim', 91719, 1, 68);
INSERT INTO sym_muehlsee.regions (id, name, zipcode, status, regions_id) VALUES (25, 'Hechlingen', 91719, 1, 80);
INSERT INTO sym_muehlsee.regions (id, name, zipcode, status, regions_id) VALUES (26, 'Wallesau', 91154, 1, 81);
INSERT INTO sym_muehlsee.regions (id, name, zipcode, status, regions_id) VALUES (27, 'Wettelsheim', 91757, 1, 82);
INSERT INTO sym_muehlsee.regions (id, name, zipcode, status, regions_id) VALUES (28, 'Unterhambach', 91710, 1, 83);
INSERT INTO sym_muehlsee.regions (id, name, zipcode, status, regions_id) VALUES (29, 'Otting', 86700, 1, 76);
INSERT INTO sym_muehlsee.regions (id, name, zipcode, status, regions_id) VALUES (30, 'Stopfenheim', 91792, 1, 70);
INSERT INTO sym_muehlsee.regions (id, name, zipcode, status, regions_id) VALUES (31, 'Meinheim', 91802, 1, 84);