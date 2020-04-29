create table room_amenities_description
(
    id          int auto_increment
        primary key,
    ra_id       int          not null,
    name        varchar(255) not null,
    description longtext     not null,
    lang        varchar(3)   not null
)
    collate = utf8mb4_unicode_ci;

INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (1, 1, 'Entfernung zum See', 'Die Entfernung von der Unterkunft bis zum See', 'de');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (2, 1, 'Distance to the lake', 'The distance from the property to the lake', 'en');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (3, 2, 'Famillienfreundlich', 'Familienfreundliche Unterkunft', 'de');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (4, 2, 'Family friendly', 'Family friendly accommodation', 'en');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (5, 3, 'Barrierefreie', 'Barrierefreie Unterkunft', 'de');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (6, 3, 'Accessible', 'Accessible accommodation', 'en');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (7, 4, 'Tiere erlaubt', 'Tiere sind in der Unterkunft erlaubt', 'de');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (8, 4, 'Pets Allowed', 'Pets are allowed in the hotel', 'en');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (9, 5, 'Liegewiese', 'Liegewiese zur erholung vorhanden', 'de');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (10, 5, 'Lawn', 'Lawn for relaxation available', 'en');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (11, 6, 'Ruhige Lage', 'Besonders ruhig gelegen', 'de');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (12, 6, 'Quiet area', 'Particularly quiet area', 'en');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (13, 7, 'Parkplatz', 'Parkplatz vorhanden', 'de');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (14, 7, 'Parking', 'Parking space available', 'en');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (15, 8, 'Fahrrad', 'Fahrrad vorhanden', 'de');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (16, 8, 'Bicycle', 'Bicycle available', 'en');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (17, 9, 'Kinderspielplatz', 'Kinderspielplatz vorhanden', 'de');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (18, 9, 'Children''s playground', 'Children''s playground available', 'en');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (19, 10, 'Sauna', 'Sauna Bereich', 'de');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (20, 10, 'Sauna', 'Sauna space', 'en');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (21, 11, 'Fitness Raum', 'Fitness Raum vorhanden', 'de');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (22, 11, 'Fitness room', 'Fitness room available', 'en');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (23, 12, 'Lounge', 'Lounge Bereich vorhanden', 'de');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (24, 12, 'Lounge', 'Lounge space available', 'en');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (25, 13, 'Grillplatz', 'Grillplatz ist vorhanden', 'de');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (26, 13, 'Barbecue', 'Barbecue area is available', 'en');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (27, 14, 'Brötchenservice', 'Brötchen werden auf Wunsch geliefert', 'de');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (28, 14, 'Bread service', 'Bread rolls can be delivered on request', 'en');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (29, 15, 'Frühstück', 'Frühstück wird angeboten', 'de');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (30, 15, 'Breakfast', 'Breakfast is offered', 'en');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (31, 16, 'Halbpension', 'Halbpension möglich', 'de');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (32, 16, 'Half board', 'Half board possible', 'en');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (33, 17, 'Einzelübernachtung', 'Einzelübernachtung', 'de');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (34, 17, 'Single night', 'Single night', 'en');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (35, 18, 'Waschmaschine', 'Waschmaschine ist vorhanden', 'de');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (36, 18, 'Washing machine', 'Washing machine is available
', 'en');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (37, 19, 'Geschirrspülmaschine', 'Geschirrspülmaschine ist vorhanden', 'de');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (38, 19, 'Dishwasher', 'Dishwasher is available', 'en');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (39, 20, 'Angeln', 'Angel ist möglich', 'de');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (40, 20, 'Fishing', 'Fishing space available', 'en');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (41, 21, 'Hallenbad', 'Hallenbad ist vor Ort', 'de');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (42, 21, 'Indoor pool', 'Indoor pool available', 'en');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (43, 22, 'Nichtraucher', 'Nichtraucherzimmer', 'de');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (44, 22, 'Non-smoking', 'Non smoking room', 'en');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (45, 23, 'Fahrradverleih', 'Fahrradverleih', 'de');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (46, 23, 'Bike rental', 'Bike rental', 'en');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (47, 24, 'W-LAN', 'Internet Zugang über W-LAN', 'de');
INSERT INTO sym_muehlsee.room_amenities_description (id, ra_id, name, description, lang) VALUES (48, 24, 'Wireless Internet access', 'Wireless Internet access', 'en');