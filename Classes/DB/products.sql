CREATE TABLE products (
    `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(150) NOT NULL UNIQUE,
    `quantity` INT NOT NULL,
    `price` FLOAT NOT NULL
);