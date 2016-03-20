
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- defender_bible
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `defender_bible`;

CREATE TABLE `defender_bible`
(
    `code` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- defender_book
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `defender_book`;

CREATE TABLE `defender_book`
(
    `chapter_count` INTEGER NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- defender_verse
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `defender_verse`;

CREATE TABLE `defender_verse`
(
    `book_id` INTEGER NOT NULL,
    `chapter_number` INTEGER NOT NULL,
    `verse_number` INTEGER NOT NULL,
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`),
    INDEX `defender_verse_fi_570e00` (`book_id`),
    CONSTRAINT `defender_verse_fk_570e00`
        FOREIGN KEY (`book_id`)
        REFERENCES `defender_book` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- defender_verse_translation
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `defender_verse_translation`;

CREATE TABLE `defender_verse_translation`
(
    `bible_id` INTEGER NOT NULL,
    `text` VARCHAR(1000) NOT NULL,
    `verse_id` INTEGER NOT NULL,
    `word_count` INTEGER NOT NULL,
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`),
    INDEX `defender_verse_translation_fi_34bfc9` (`bible_id`),
    INDEX `defender_verse_translation_fi_a9051e` (`verse_id`),
    CONSTRAINT `defender_verse_translation_fk_34bfc9`
        FOREIGN KEY (`bible_id`)
        REFERENCES `defender_bible` (`id`),
    CONSTRAINT `defender_verse_translation_fk_a9051e`
        FOREIGN KEY (`verse_id`)
        REFERENCES `defender_verse` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- defender_tag
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `defender_tag`;

CREATE TABLE `defender_tag`
(
    `verse_id` INTEGER NOT NULL,
    `vote_count` INTEGER DEFAULT 0,
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`),
    INDEX `defender_tag_fi_a9051e` (`verse_id`),
    CONSTRAINT `defender_tag_fk_a9051e`
        FOREIGN KEY (`verse_id`)
        REFERENCES `defender_verse` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- defender_tag_translation
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `defender_tag_translation`;

CREATE TABLE `defender_tag_translation`
(
    `bible_id` INTEGER NOT NULL,
    `relevant_words` VARCHAR(255) NOT NULL,
    `tag_id` INTEGER NOT NULL,
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`),
    INDEX `defender_tag_translation_fi_34bfc9` (`bible_id`),
    INDEX `defender_tag_translation_fi_f5ffad` (`tag_id`),
    CONSTRAINT `defender_tag_translation_fk_34bfc9`
        FOREIGN KEY (`bible_id`)
        REFERENCES `defender_bible` (`id`),
    CONSTRAINT `defender_tag_translation_fk_f5ffad`
        FOREIGN KEY (`tag_id`)
        REFERENCES `defender_tag` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- defender_tag_vote
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `defender_tag_vote`;

CREATE TABLE `defender_tag_vote`
(
    `tag_id` INTEGER NOT NULL,
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`),
    INDEX `defender_tag_vote_fi_f5ffad` (`tag_id`),
    CONSTRAINT `defender_tag_vote_fk_f5ffad`
        FOREIGN KEY (`tag_id`)
        REFERENCES `defender_tag` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- defender_topic
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `defender_topic`;

CREATE TABLE `defender_topic`
(
    `name` VARCHAR(255) NOT NULL,
    `tag_count` INTEGER DEFAULT 0 NOT NULL,
    `tree_left` INTEGER,
    `tree_right` INTEGER,
    `tree_level` INTEGER,
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- defender_topic_adoptee
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `defender_topic_adoptee`;

CREATE TABLE `defender_topic_adoptee`
(
    `adoptee_id` INTEGER NOT NULL,
    `topic_id` INTEGER NOT NULL,
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`),
    INDEX `defender_topic_adoptee_fi_e13667` (`topic_id`),
    CONSTRAINT `defender_topic_adoptee_fk_e13667`
        FOREIGN KEY (`topic_id`)
        REFERENCES `defender_topic` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- defender_topic_lesson
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `defender_topic_lesson`;

CREATE TABLE `defender_topic_lesson`
(
    `lesson_id` INTEGER NOT NULL,
    `topic_id` INTEGER NOT NULL,
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`),
    INDEX `defender_topic_lesson_fi_df0bc4` (`lesson_id`),
    INDEX `defender_topic_lesson_fi_e13667` (`topic_id`),
    CONSTRAINT `defender_topic_lesson_fk_df0bc4`
        FOREIGN KEY (`lesson_id`)
        REFERENCES `defender_lesson` (`id`),
    CONSTRAINT `defender_topic_lesson_fk_e13667`
        FOREIGN KEY (`topic_id`)
        REFERENCES `defender_topic` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- defender_topic_synonym
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `defender_topic_synonym`;

CREATE TABLE `defender_topic_synonym`
(
    `name` VARCHAR(255) NOT NULL,
    `topic_id` INTEGER NOT NULL,
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`),
    INDEX `defender_topic_synonym_fi_e13667` (`topic_id`),
    CONSTRAINT `defender_topic_synonym_fk_e13667`
        FOREIGN KEY (`topic_id`)
        REFERENCES `defender_topic` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- defender_topic_tag
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `defender_topic_tag`;

CREATE TABLE `defender_topic_tag`
(
    `tag_id` INTEGER NOT NULL,
    `topic_id` INTEGER NOT NULL,
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`),
    INDEX `defender_topic_tag_fi_f5ffad` (`tag_id`),
    INDEX `defender_topic_tag_fi_e13667` (`topic_id`),
    CONSTRAINT `defender_topic_tag_fk_f5ffad`
        FOREIGN KEY (`tag_id`)
        REFERENCES `defender_tag` (`id`),
    CONSTRAINT `defender_topic_tag_fk_e13667`
        FOREIGN KEY (`topic_id`)
        REFERENCES `defender_topic` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- defender_lesson
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `defender_lesson`;

CREATE TABLE `defender_lesson`
(
    `summary` VARCHAR(1000) NOT NULL,
    `tag_count` INTEGER DEFAULT 0 NOT NULL,
    `tree_left` INTEGER,
    `tree_right` INTEGER,
    `tree_level` INTEGER,
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- defender_lesson_tag
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `defender_lesson_tag`;

CREATE TABLE `defender_lesson_tag`
(
    `lesson_id` INTEGER NOT NULL,
    `tag_id` INTEGER NOT NULL,
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`),
    INDEX `defender_lesson_tag_fi_df0bc4` (`lesson_id`),
    INDEX `defender_lesson_tag_fi_f5ffad` (`tag_id`),
    CONSTRAINT `defender_lesson_tag_fk_df0bc4`
        FOREIGN KEY (`lesson_id`)
        REFERENCES `defender_lesson` (`id`),
    CONSTRAINT `defender_lesson_tag_fk_f5ffad`
        FOREIGN KEY (`tag_id`)
        REFERENCES `defender_tag` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- defender_answer
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `defender_answer`;

CREATE TABLE `defender_answer`
(
    `answer_type_id` INTEGER NOT NULL,
    `response_id` INTEGER NOT NULL,
    `text` VARCHAR(255) NOT NULL,
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`),
    INDEX `defender_answer_fi_bdd93b` (`answer_type_id`),
    INDEX `defender_answer_fi_210158` (`response_id`),
    CONSTRAINT `defender_answer_fk_bdd93b`
        FOREIGN KEY (`answer_type_id`)
        REFERENCES `defender_answer_type` (`id`),
    CONSTRAINT `defender_answer_fk_210158`
        FOREIGN KEY (`response_id`)
        REFERENCES `defender_response` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- defender_answer_type
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `defender_answer_type`;

CREATE TABLE `defender_answer_type`
(
    `value` VARCHAR(255) NOT NULL,
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- defender_response
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `defender_response`;

CREATE TABLE `defender_response`
(
    `explanation` VARCHAR(255),
    `text` VARCHAR(255) NOT NULL,
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- defender_statement
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `defender_statement`;

CREATE TABLE `defender_statement`
(
    `response_id` INTEGER NOT NULL,
    `text` VARCHAR(255) NOT NULL,
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`),
    INDEX `defender_statement_fi_210158` (`response_id`),
    CONSTRAINT `defender_statement_fk_210158`
        FOREIGN KEY (`response_id`)
        REFERENCES `defender_response` (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;