BileMo
Repository containing the code for OpenClassrooms PHP Application Developer Path Project 7.

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/a04585b6f6a84b7eb998a4b11efc1ae6)](https://www.codacy.com/gh/Marc-Alban/Bilemo-OC-P7/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Marc-Alban/Bilemo-OC-P7&amp;utm_campaign=Badge_Grade)


Installation Instructions:
Clone or download the contents of the GitHub repository: git clone https://github.com/quentinboinet/bilemo.git
Create your DB with the file located at the root entitled ". env" then added . local to replace the database setting values.
Install project dependencies with: composer install or update
Create the database with the following command: php bin/console doctrine:database:create
Initialized the migration with the command: php bin/console migration:migrate
Run migrations with the command: php bin/console doctrine:migrations:migrate
Then import the initial dataset with: php bin/console doctrine:fixtures:load
To run the server, run the command php bin/console server:run or symfony serve -d
Bravo, your API is now available at: localhost:8000! You can test it via Postman or any other tool of your choice.
