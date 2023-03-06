CREATE TABLE productUser (
    `userId` INT NOT NULL,
    `productId` INT NOT NULL,
    `saleId` INT NOT NULL,
    `quantity` INT NOT NULL,
    FOREIGN KEY (userId) REFERENCES users(id),
    FOREIGN KEY (saleId) REFERENCES sales(id),
    FOREIGN KEY (productId) REFERENCES products(id),
    PRIMARY KEY (userId, productId)
);