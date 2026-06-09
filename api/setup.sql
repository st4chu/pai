CREATE DATABASE IF NOT EXISTS rest
USE rest

CREATE TABLE events(
    id int PRIMARY KEY,
    date datetime NOT NULL,
    header varchar(32) NOT NULL,
    note varchar(32)
)