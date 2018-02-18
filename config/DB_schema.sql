CREATE TABLE download
(
    id INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    month INT(11) NOT NULL,
    day INT(11) NOT NULL,
    year INT(11) NOT NULL,
    hour INT(11) NOT NULL,
    unit_id INT(11) NOT NULL,
    value INT(11) NOT NULL
);
CREATE UNIQUE INDEX download_id_uindex ON download (id);
CREATE TABLE latency
(
    id INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    month INT(11) NOT NULL,
    day INT(11) NOT NULL,
    year INT(11) NOT NULL,
    hour INT(11) NOT NULL,
    unit_id INT(11) NOT NULL,
    value INT(11) NOT NULL
);
CREATE UNIQUE INDEX latency_id_uindex ON latency (id);
CREATE TABLE migrations
(
    id INT(10) unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT,
    migration VARCHAR(255) NOT NULL,
    batch INT(11) NOT NULL
);
CREATE TABLE packet_loss
(
    id INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    month INT(11) NOT NULL,
    day INT(11) NOT NULL,
    year INT(11) NOT NULL,
    hour INT(11) NOT NULL,
    unit_id INT(11) NOT NULL,
    value INT(11) NOT NULL
);
CREATE UNIQUE INDEX packet_loss_id_uindex ON packet_loss (id);
CREATE TABLE upload
(
    id INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    month INT(11) NOT NULL,
    day INT(11) NOT NULL,
    year INT(11) NOT NULL,
    hour INT(11) NOT NULL,
    unit_id INT(11) NOT NULL,
    value INT(11) NOT NULL
);
CREATE UNIQUE INDEX upload_id_uindex ON upload (id);