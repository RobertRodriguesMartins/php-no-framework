CREATE TABLE users (
    `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `login` VARCHAR(150) NOT NULL,
    `token` VARCHAR(150) NOT NULL,
    `token_expire_date` DATE NOT NULL 
);