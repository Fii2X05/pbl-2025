create type user_role as enum ('admin', 'editor', 'member');

create table users (
    user_id serial primary key,
    username varchar(50) unique not null,
    password_hash varchar(255) not null,
    full_name varchar(100),
    email varchar(100) unique,
    role user_role default 'member',
    is_active BOOLEAN DEFAULT TRUE, 
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp
);

create type member_type as enum ('dosen', 'laboran');

create table team_members (
    member_id serial primary key,
    full_name varchar(100) not null,
    position varchar(100), 
    type member_type not null,
    photo_url varchar(255),
    bio text,
    status VARCHAR(20) default 'PUBLISHED', 
    created_at timestamp
);