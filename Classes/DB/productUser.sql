CREATE TABLE productUser (
    `userId` INT NOT NULL,
    `productId` INT NOT NULL,
    `quantity` INT NOT NULL,
    FOREIGN KEY (userId) REFERENCES users(id),
    FOREIGN KEY (productId) REFERENCES products(id),
    PRIMARY KEY (userId, productId)
);