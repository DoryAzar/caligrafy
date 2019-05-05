DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id int(15) NOT NULL AUTO_INCREMENT,
    username varchar(100) NOT NULL,
    passcode varchar(100) NOT NULL,
    permissions tinyint UNSIGNED DEFAULT 0,
    created_at datetime NOT NULL DEFAULT now(),
    modified_at datetime NOT NULL DEFAULT now() ON UPDATE now(),
    PRIMARY KEY (id)
)ENGINE = InnoDB DEFAULT CHARSET = utf8;

