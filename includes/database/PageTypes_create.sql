CREATE TABLE IF NOT EXISTS `PageTypes` (
    ID BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(128) NOT NULL,
    ShortDescription TEXT,
    LongDescription TEXT
) ENGINE=InnoDB;

INSERT INTO `PageTypes`
	(Name, ShortDescription, LongDescription)
	VALUES
	('text', 'Pages with mainly text and pictures.', 'Simples pages with text. Just text. And pictures, but mostly text. Best used for basic, general content.');
