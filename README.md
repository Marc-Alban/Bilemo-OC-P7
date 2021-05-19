BileMo
Dépôt contenant le code pour le projet 7 du parcours développeur d'applications PHP d'OpenClassrooms.

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/a04585b6f6a84b7eb998a4b11efc1ae6)](https://www.codacy.com/gh/Marc-Alban/Bilmo-OC-P7/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Marc-Alban/Bilmo-OC-P7&amp;utm_campaign=Badge_Grade)


Instructions d'installation :
Clonez ou téléchargez le contenu du dépôt GitHub : git clone https://github.com/quentinboinet/bilemo.git
Créer votre DB avec le fichier situé à la racine intitulé ".env" puis rajouté .local afin de remplacer les valeurs de paramétrage de la base de données.
Installez les dépendances du projet avec : composer install ou update
Créez la base de données avec la commande suivante : php bin/console doctrine:database:create
Initialisé la migration avec la commande: php bin/console migration:migrate
Lancer les migrations avec la commande : php bin/console doctrine:migrations:migrate
Importez ensuite le jeu de données initiales avec : php bin/console doctrine:fixtures:load
Afin de lancer le serveur, lancez la commande php bin/console server:run ou symfony serve -d
Bravo, votre API est désormais accessible à l'adresse : localhost:8000 ! Vous pouvez la tester via Postman ou tout autre outil de votre choix.