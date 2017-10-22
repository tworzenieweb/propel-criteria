CREATE TABLE `book`
(
    `id` INTEGER NOT NULL PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `isbn` VARCHAR(24) NOT NULL,
    `publisher_id` INTEGER NOT NULL,
    `author_id` INTEGER NOT NULL
);