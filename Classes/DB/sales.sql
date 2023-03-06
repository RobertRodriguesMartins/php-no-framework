CREATE TABLE sales (
    `id` PRIMARY KEY INT NOT NULL,
    `userId` INT NOT NULL,
    FOREIGN KEY (userId) REFERENCES users(id),
);