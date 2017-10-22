CREATE TABLE `author`
(
    `id` INTEGER NOT NULL PRIMARY KEY,
    `first_name` VARCHAR(128) NOT NULL,
    `last_name` VARCHAR(128) NOT NULL
);

CREATE TABLE `publisher`
(
    `id` INTEGER NOT NULL PRIMARY KEY,
    `name` VARCHAR(128) NOT NULL
);

CREATE TABLE `book`
(
    `id` INTEGER NOT NULL PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `isbn` VARCHAR(24) NOT NULL,
    `publisher_id` INTEGER NOT NULL,
    `author_id` INTEGER NOT NULL
);