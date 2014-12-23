CREATE TABLE IF NOT EXISTS `TextPages` (
    PageID BIGINT NOT NULL PRIMARY KEY,
    Content LONGTEXT NOT NULL DEFAULT "",
    ParseMarkdown BIT NOT NULL DEFAULT 0,
    ParsePHP BIT NOT NULL DEFAULT 0,
	EscapeHTML BIT NOT NULL DEFAULT 0,
    
    CONSTRAINT fk_textpages_pages
        FOREIGN KEY (PageID)
        REFERENCES Pages(ID)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

INSERT INTO Pages (Type, Name, Title)
VALUES (1, 'home', 'Home');

INSERT INTO TextPages (PageID, Content, ParseMarkdown, ParsePHP)
VALUES (1, '<?php echo "Test content. *please ignore*."; ?>', 1, 1);
