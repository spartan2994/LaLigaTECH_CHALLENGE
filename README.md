# LaLigaTECH_CHALLENGE


1.- Install Symfony 

Installer — curl

- curl -sS https://get.symfony.com/cli/installer | bash

Install Homebrew

- /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

Install CLI

- brew install symfony-cli/tap/symfony-cli

More information: https://symfony.com/download

2.- Go to project (LaLiga_Tech_Test) and check the Symfony requirements 

CLONE GITHUB REPOSITORY
- (git clone https://github.com/spartan2994/LaLigaTECH_CHALLENGE.git)
  

Symfony Requirements
- symfony check:requirements

Symfony Requirements Checker
~~~~~~~~~~~~~~~~~~~~~~~~~~~~

> PHP is using the following php.ini file:
/opt/homebrew/etc/php/8.2/php.ini

> Checking Symfony requirements:

...................................

                                              
 [OK]                                         
 Your system is ready to run Symfony projects 
                                              

Note  The command console can use a different php.ini file
~~~~  than the one used by your web server.
      Please check that both the console and the web server
      are using the same PHP version and configuration.

~~~~~~~~~~~~~~~~~~~~~~~~~~~~

3.- Start symfony server

Start Server 

- symfony server:start

4.- Import SQL Dump File (LLT_2023_07_18,sql) to Mysql and check DATABASE_URL 

Check .env file
-  DATABASE_URL="mysql://root:root@127.0.0.1:3306/LLT?serverVersion=5.7.39&charset=utf8mb4"

5.- Test Api´s with Postman Collection file (LLT.postman_collection.json)

Import Postman Collection
- File->Import, and upload file LLT.postman_collection.json
