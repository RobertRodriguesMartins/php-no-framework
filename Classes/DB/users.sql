CREATE TABLE users (
    `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `token` VARCHAR(255) NOT NULL,
    `token_expire_date` DATE NOT NULL 
);