CREATE TABLE productUser (
    `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `userId` INT NOT NULL,
    `productId` INT NOT NULL,
    FOREIGN KEY (userId) REFERENCES users(id),
    FOREIGN KEY (productId) REFERENCES products(id)
);