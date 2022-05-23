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

## Création des classes & données

Création du projet Symfony via composer (incluant Doctrine et Twig)

```shell
composer create-project symfony/skeleton snow-tricks
cd snow-tricks
composer require webapp
```

### Entités

Créer la base de données -> ```php bin/console doctrine:database:create```  
Créer une entité user avec les standards de sécurité -> ```php bin/console make:user```  
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

## Sécurité des authentifications

L'entité user a été créée avec les autres entités  
Créer maintenant les formulaires de connexion, d'inscription et d'oubli de mot de passe

```shell
composer require symfonycasts/reset-password-bundle
composer require symfonycasts/verify-email-bundle
php bin/console make:auth
php bin/console make:registration-form
php bin/console make:reset-password
```

### Envoyer un mail de confirmation de l'inscription 
issu du choix dans la commande make:registration-form
```shell 
composer require symfony/mailer
```
Puis personnalier le redirectToRoute dans le RegistrationController.php  
Pour enlever la connexion automatique après l'inscription (mauvais choix dans le cli), décommenter ```return $userAuthenticator->authenticateUser```  
Attention les mails envoyés sont paramétrés en asynchrone, modifier ce paramètre dans le fichier ```config/packages/messenger.yaml``` en décommentant ```sync: 'sync://'``` et en paramétrant ```SendEmailMessage: sync```    

Avec docker-compose/maildev, dans le fichier ```.env``` définir ```MAILER_DSN=smtp://127.0.0.1:25```, 25 étant le port exposé dans le fichier ```docker-compose.yml```  

### Autoriser la connexion seulement pour les utilisateurs vérifiés
Dans ```src/Security``` créer le fichier UserChecker.php en injectant sa dépendance, créer les fonctions ```checkPreAuth``` et ```checkPostAuth```  
Puis dans le fichier ```config/packages/security.yaml``` définir
```yaml
security:
  firewalls:
    main:
      pattern: ^/
      user_checker: App\Security\UserChecker
```

### Ajouter un champ de confirmation de mot de passe (login)  
Dans le RegistrationFormType.php, modifier le champ ```PasswordType::class``` par : 
```php
->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'], 
```

### Ajout du champ "Rester connecté"
Dans le fichier ```config/packages/security.yaml``` définir
```yaml
remember_me:
  secret: '%kernel.secret%'
  lifetime: 31536000 # 1 year
```
Puis créer le contenu checkbox "Rester connecté" dans le fichier ```templates/security/login.html.twig```

## Upload de fichiers

### Installation de VichUploaderBundle
```shell
composer require vich/uploader-bundle
```

### Paramétrage de VichUploaderBundle
Dans le fichier ```config/services.yaml``` déterminer le chemin d'enregistrement des fichiers :
```yaml
parameters:
  trick_media: /uploads/images/
```
Dans le fichier ```config/packages/vich_uploader.yaml``` récupérer le chemin d'enregistrement, définir le chemin racine à utiliser, s'assurer que le nom du fichier est unique et préserve les fichiers en cas de suppession/modification des tricks :
```yaml
mappings:
  products:
    uri_prefix: '%trick_media%'
    upload_destination: '%kernel.project_dir%/public%trick_media%'
    namer: Vich\UploaderBundle\Naming\SmartUniqueNamer
    delete_on_update: false
    delete_on_remove: false
```

