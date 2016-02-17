# The Bible App

### Installing Composer
###### Create composer.json
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
###### Download composer.phar
	wget http://getcomposer.org/composer.phar
###### Install Composer
	php composer.phar install

### Setup Propel
###### Open bashrc file
	vim ~/.bashrc
###### Add Propel to bash (as per example) and save
	export PATH=$PATH:~/Documents/bible/vendor/bin/
###### If necessary, read bashrc file
	source ~/.bashrc
###### Initialise project
	propel init
###### Convert config to .php file
	propel config:convert

### Build database and models
###### Generate SQL (adding --overwrite if necessary)
	propel sql:build
	propel sql:build --overwrite
###### Run SQL
	propel sql:insert
###### Build models
	propel model:build
###### Populate Composer autoloader
	php composer.phar dump-autoload