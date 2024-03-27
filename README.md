# Bienvenue sur Le Charnet de Santé

J'ai développé ce projet en vue de la soutenance du titre professionnel RNCP Bac+2 Développeur web et web mobile.

Ce site est un carnet de santé pour chat en ligne, gratuit et sécurisé.

Il permet de centraliser et enregistrer toutes les informations relatives à la vie quotidienne, à la santé ou aux soins apportés à votre chat.

Vous pourrez également y stocker les documents importants (analyses, ordonnances, etc) et partager les informations sur votre chat avec votre vétérinaire en toute sécurité.

## Installation

__*Pour installer le projet en local :*__
* `git clone https://github.com/AurelieGilet/charnet-de-sante.git`
* `composer install`

__*Configuration connexion BDD dans le fichier .env :*__
* DATABASE_URL="mysql://root@127.0.0.1:3306/charnet_de_sante?serverVersion=mariadb-10.4.11" (pour le serveur Xampp)

__*Configuration Symfony Mailer dans le fichier .env :*__
* MAILER_DSN=gmail+smtp://email_address:password@default

SI vous souhaitez tester les fonctionnalités de contact et de réinitialisation de mot de passe, vous devrez indiquer une adresse email dans le .env et modifier les fichier nécessaires :
- Dans **ContactController.php** ligne 28 => changer l'adresse email de destination
- Dans **ResetPasswordController.php** ligne 156 => changer l'adresse email de l'expéditeur
- Dans **contact.html.twig** ligne 18 => changer l'adresse email du "mailto"

__*Création de la BDD, migration des tables et chargement des fixtures :*__
* `php bin/console doctrine:database:create`
* `php bin/console doctrine:migrations:migrate`
* `php bin/console doctrine:fixtures:load`

__*Création de la BDD de test pour PHPUnit, migration des tables et chargement des fixtures :*__
* `php bin/console doctrine:database:create --env=test`
* `php bin/console doctrine:migrations:migrate --env=test`
* `php bin/console doctrine:fixtures:load --env=test`

__*Installation de Webpack Encore :*__
* `yarn install --force` (ou npm)
* `yarn watch`

__*Il ne vous reste plus qu'à lancer le serveur :*__
* `symfony server:start`

Pour pouvoir explorer le site, vous disposez de 5 comptes utilisateur : un admin et 4 users.  
Pour connecter l'admin : admin@mail.com / Password0  
Pour connecter un user : fakeuser1@mail.com / Password1 (remplacer les 1 par 2, 3 ou 4 pour les autres users)  
