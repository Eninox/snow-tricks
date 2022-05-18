# Site Snow Tricks

Projet fin de cycle Developpeur Web et Web Mobile 2022 (DWWM) à l'Institut d'Informatique Appliquée (IIA).

## Démarrage du projet

### 1-Choisir l'environnement

#### WAMP : 
- créer le fichier .env.local  
- déterminer les paramètres de DATABASE_URL (Mysql, Postgresql, ...)

#### Docker-compose : 
- démarrer les conteneurs mysql / phpmyadmin / maildev (voir fichier docker-compose.yml) avec la commande :
```shell
docker-compose up -d
```

### 2- Installer les dépendances et créer la base de données

```shell
npm install
composer install
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```

### 3- Démarrer le serveur PHP

```shell
php -S localhost:8000 -t public
```

_______________________________________________________________________________________________________________

## Création du projet

Création du projet Symfony via composer (incluant Doctrine et Twig)

```shell
composer create-project symfony/skeleton snow-tricks
cd snow-tricks
composer require webapp
```

### Entités

Créer la base de données -> ```php bin/console doctrine:database:create```  
Créer une entité -> ```php bin/console make:entity```  
Générer les migrations -> ```php bin/console make:migration```  
Exécuter les migrations -> ```php bin/console doctrine:migration:migrate```  

#### En cas de problème dans l'exécution des migrations  

Supprimer les fichiers PHP de migration puis :

```shell
php bin/console doctrine:database:drop --force
php bin/console doctrine:database:create
php bin/console make:migration # Cette commande va générer 1 seul fichier de migration contenant l'ensemble des requêtes SQL pour créer la base de données
php bin/console doctrine:migrations:migrate
```

### Fixtures
```shell
composer require --dev orm-fixtures
```
Générer des fixtures -> ```php bin/console make:fixture```  
Après personnalisation des fixtures dans le dossier ```src/DataFixtures/```  
Exécuter les fixtures -> ```php bin/console doctrine:fixtures:load```  

