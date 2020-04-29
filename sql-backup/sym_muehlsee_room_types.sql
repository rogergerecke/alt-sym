create table room_types
(
    id                 int auto_increment
        primary key,
    hostel_id          int          not null,
    booking_fee        double       not null,
    breakfast_included tinyint(1)   not null,
    currency           varchar(3)   not null,
    discounts          json         null,
    final_rate         double       not null,
    free_cancellation  tinyint(1)   not null,
    hotel_fee          double       not null,
    rate_type          varchar(7)   null,
    local_tax          double       not null,
    meal_code          varchar(2)   not null,
    landing_page_url   varchar(255) null,
    net_rate           double       not null,
    payment_type       varchar(255) not null,
    resort_fee         double       not null,
    room_amenities     json         null,
    room_code          varchar(255) not null,
    service_charge     double       not null,
    url                varchar(255) null,
    vat                double       not null
)
    collate = utf8mb4_unicode_ci;

