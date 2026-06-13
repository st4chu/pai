CREATE TABLE users(
    id int AUTO_INCREMENT PRIMARY KEY,
    login varchar(32) NOT NULL,
    password varchar(255) NOT NULL
);

CREATE table events(
	id int AUTO_INCREMENT PRIMARY KEY,
	owner int not null,
    date datetime not null,
    header varchar(255) not null,
    note varchar(255)
);
