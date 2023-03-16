CREATE DATABASE IF NOT EXISTS price_db;

USE price_db;

CREATE TABLE users (
    `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `token` VARCHAR(255) NOT NULL,
    `token_expire_date` DATE NOT NULL 
);

CREATE TABLE marks (
    `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE categories (
    `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE stores (
    `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(150) NOT NULL UNIQUE
);

CREATE TABLE sellers (
    `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(150) NOT NULL UNIQUE,
    `store_id` INT NOT NULL,
    FOREIGN KEY (store_id) REFERENCES stores(id)
);

CREATE TABLE products (
    `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL UNIQUE,
    `status` TINYINT NOT NULL,
    `mark_id` INT NOT NULL,
    `store_id` INT NOT NULL,
    `category_id` INT NOT NULL,
    FOREIGN KEY (mark_id) REFERENCES marks(id),
    FOREIGN KEY (store_id) REFERENCES stores(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE offers (
    `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `price` FLOAT NOT NULL,
    `product_id` INT NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(id)
);