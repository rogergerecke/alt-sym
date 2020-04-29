create table migration_versions
(
    version     varchar(14) not null
        primary key,
    executed_at datetime    not null comment '(DC2Type:datetime_immutable)'
)
    collate = utf8mb4_unicode_ci;

INSERT INTO sym_muehlsee.migration_versions (version, executed_at) VALUES ('20200405094817', '2020-04-05 10:06:14');
INSERT INTO sym_muehlsee.migration_versions (version, executed_at) VALUES ('20200405114213', '2020-04-05 11:42:27');
INSERT INTO sym_muehlsee.migration_versions (version, executed_at) VALUES ('20200405200048', '2020-04-05 20:01:12');
INSERT INTO sym_muehlsee.migration_versions (version, executed_at) VALUES ('20200407144020', '2020-04-07 19:25:25');
INSERT INTO sym_muehlsee.migration_versions (version, executed_at) VALUES ('20200407172211', '2020-04-07 19:26:25');
INSERT INTO sym_muehlsee.migration_versions (version, executed_at) VALUES ('20200407173543', '2020-04-10 09:12:11');
INSERT INTO sym_muehlsee.migration_versions (version, executed_at) VALUES ('20200410070921', '2020-04-10 09:12:14');
INSERT INTO sym_muehlsee.migration_versions (version, executed_at) VALUES ('20200412061813', '2020-04-12 08:38:46');
INSERT INTO sym_muehlsee.migration_versions (version, executed_at) VALUES ('20200412063535', '2020-04-12 08:39:05');
INSERT INTO sym_muehlsee.migration_versions (version, executed_at) VALUES ('20200412070023', '2020-04-12 07:03:08');
INSERT INTO sym_muehlsee.migration_versions (version, executed_at) VALUES ('20200412081052', '2020-04-12 08:11:44');
INSERT INTO sym_muehlsee.migration_versions (version, executed_at) VALUES ('20200412180149', '2020-04-12 18:02:22');
INSERT INTO sym_muehlsee.migration_versions (version, executed_at) VALUES ('20200412185942', '2020-04-12 18:59:48');
INSERT INTO sym_muehlsee.migration_versions (version, executed_at) VALUES ('20200414202953', '2020-04-14 20:30:35');
INSERT INTO sym_muehlsee.migration_versions (version, executed_at) VALUES ('20200414203742', '2020-04-14 20:37:54');
INSERT INTO sym_muehlsee.migration_versions (version, executed_at) VALUES ('20200414205100', '2020-04-14 20:51:16');
INSERT INTO sym_muehlsee.migration_versions (version, executed_at) VALUES ('20200418144056', '2020-04-18 14:41:19');
INSERT INTO sym_muehlsee.migration_versions (version, executed_at) VALUES ('20200419174321', '2020-04-19 17:43:37');
INSERT INTO sym_muehlsee.migration_versions (version, executed_at) VALUES ('20200420051151', '2020-04-20 05:11:59');