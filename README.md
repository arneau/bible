# The Defender App

### Install Composer with Propel
###### 1) Create composer.json
	{
		"require": {
			"propel/propel": "~2.0@dev"
		},
		"autoload": {
			"classmap": [
				"app/models/"
			]
		}
	}
###### 2) Download composer.phar
	wget http://getcomposer.org/composer.phar
###### 3) Install Composer
	php composer.phar install

### Setup Propel
###### 1) Open bashrc file
	vim ~/.bashrc
###### 2) Add Propel to bash (as per example) and save
	export PATH=$PATH:~/Documents/defender-app/vendor/bin/
###### 3) If necessary, read bashrc file
	source ~/.bashrc
###### 4) Initialise project
	propel init
###### 5) Convert config to .php file
	propel config:convert

### Build database and models
###### 1) Generate SQL (adding --overwrite if necessary)
	propel sql:build
	propel sql:build --overwrite
###### 2) Run SQL
	propel sql:insert
###### 3) Build models
	propel model:build
###### 4) Populate Composer autoloader
	php composer.phar dump-autoload

### Back up database
###### 1) Navigate to backup directory
	cd backups/
###### 2) Backup entire database
	mysqldump -u root defender-app > db.sql
or
###### 3) Backup data only
	mysqldump -u root defender-app > db.sql --no-create-db --no-create-info
or
###### 4) Backup specific tables' data
	mysqldump -u root defender-app defender_answer defender_answer_type defender_lesson defender_lesson_parent defender_lesson_note defender_lesson_tag defender_note defender_note_content defender_response defender_statement defender_tag defender_tag_highlighter defender_tag_verse defender_tag_vote defender_topic defender_topic_parent defender_topic_lesson defender_topic_note defender_topic_synonym defender_topic_tag > data.sql --no-create-db --no-create-info

### Online update
    SET FOREIGN_KEY_CHECKS = 0;
    
    TRUNCATE defender_answer;
    TRUNCATE defender_answer_type;
    TRUNCATE defender_lesson;
    TRUNCATE defender_lesson_parent;
    TRUNCATE defender_lesson_note;
    TRUNCATE defender_lesson_tag;
    TRUNCATE defender_note;
    TRUNCATE defender_note_content;
    TRUNCATE defender_response;
    TRUNCATE defender_statement;
    TRUNCATE defender_tag;
    TRUNCATE defender_tag_highlighter;
    TRUNCATE defender_tag_verse;
    TRUNCATE defender_tag_vote;
    TRUNCATE defender_topic;
    TRUNCATE defender_topic_parent;
    TRUNCATE defender_topic_lesson;
    TRUNCATE defender_topic_note;
    TRUNCATE defender_topic_synonym;
    TRUNCATE defender_topic_tag;
    
    SET FOREIGN_KEY_CHECKS = 1;