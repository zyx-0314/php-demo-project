CREATE TABLE IF NOT EXISTS public."user" (
    id uuid NOT NULL PRIMARY KEY,
    first_name varchar(225) NOT NULL,
    middle_name varchar(225),
    last_name varchar(225) NOT NULL,
    password varchar(225) NOT NULL,
    username varchar NOT NULL
);