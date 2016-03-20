# The Defender App

### Install Composer
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
	mysqldump -u root --databases defender-app > db.sql
or
###### 3) Backup data only
	mysqldump -u root --databases defender-app --no-create-db --no-create-info > db.sql