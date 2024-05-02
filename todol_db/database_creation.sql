-- Before you running the scripts, follow these steps:
-- - create a db named 'todol_db'
-- - set to the PHP project (file: todol_logic/.env) the owner user that you set to the created db (code line: DB_USERNAME=owner)
-- - config the other db connection properties there:
--      DB_CONNECTION=pgsql
--      DB_HOST=localhost
--      DB_PORT=<postgres port>
--      DB_DATABASE=todol_db
-- - finally, in the 'todol_db' database, in 'public' schema, run the following scripts:

-- creating user table
create table users
(
    id       serial
        constraint users_pk
            primary key,
    username varchar(128)
        constraint users_uk_2
            unique,
    password varchar(128)
);

alter table users
    owner to owner;

-- creating todo table
create table todos
(
    id          serial
        constraint todo_pk
            primary key,
    user_id     integer
        constraint user_id_fk
            references users
            on update cascade on delete cascade,
    name        varchar(128) not null
        constraint todo_uk
            unique,
    description varchar(256) default NULL::character varying,
    finish_date timestamp    default CURRENT_TIMESTAMP
);

alter table todos
    owner to owner;

-- creating todo_item table
create table todo_items
(
    id      serial
        constraint todo_item_pk
            primary key,
    todo_id integer
        constraint todo_id_fk
            references todos
            on update cascade on delete cascade,
    name    text                                      not null,
    status  varchar default 'todo'::character varying not null
);

alter table todo_items
    owner to owner;