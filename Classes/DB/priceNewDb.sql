CREATE DATABASE IF NOT EXISTS price_db;

USE price_db;

CREATE TABLE user (
    `id_user` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `user_email` VARCHAR(255) NOT NULL UNIQUE,
    `user_password` VARCHAR(255) NOT NULL,
    `user_token` VARCHAR(255) NOT NULL,
    `user_token_expire` DATE NOT NULL 
);

CREATE TABLE mark (
    `id_mark` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `mark_name` VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE category (
    `id_category` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `category_name` VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE store (
    `id_store` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `store_name` VARCHAR(150) NOT NULL UNIQUE
);

CREATE TABLE seller (
    `id_seller` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `seller_name` VARCHAR(150) NOT NULL UNIQUE,
    `store_id` INT NOT NULL,
    FOREIGN KEY (store_id) REFERENCES store(id_store)
);

CREATE TABLE product (
    `id_product` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `product_name` VARCHAR(255) NOT NULL UNIQUE,
    `product_status` VARCHAR(255) NOT NULL,
    `mark_id` INT NOT NULL,
    `store_id` INT NOT NULL,
    `category_id` INT NOT NULL,
    FOREIGN KEY (mark_id) REFERENCES mark(id_mark),
    FOREIGN KEY (store_id) REFERENCES store(id_store),
    FOREIGN KEY (category_id) REFERENCES category(id_category)
);

CREATE TABLE spec (
    `id_spec` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `product_id` INT NOT NULL,
    `spec_name` VARCHAR(255) NOT NULL,
    `spec_value` VARCHAR(255) NOT NULL,
    FOREIGN KEY (product_id) REFERENCES product(id_product)
);

CREATE TABLE offer (
    `id_offer` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `offer_price` FLOAT NOT NULL,
    `product_id` INT NOT NULL,
    FOREIGN KEY (product_id) REFERENCES product(id_product)
);