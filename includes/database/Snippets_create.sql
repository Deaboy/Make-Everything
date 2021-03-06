CREATE TABLE IF NOT EXISTS `Snippets` (
	ID BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	Title VARCHAR(256) NOT NULL,
    Content TEXT NOT NULL,
    ParseMarkdown BIT NOT NULL DEFAULT 0,
    ParsePHP BIT NOT NULL DEFAULT 0,
    
    CHECK (Title <> ''),
    
	INDEX ind_snippets_title (Title ASC)
) ENGINE=InnoDB;
