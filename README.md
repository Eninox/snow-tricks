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
Pour enlever la connexion automatique après l'inscription (mauvais choix dans le cli), commenter ```return $userAuthenticator->authenticateUser```  
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

### Ajouter un champ de confirmation de mot de passe (inscription)  
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
  trick_main_picture: /uploads/images
  trick_media: /uploads/media
```


Dans le fichier ```config/packages/vich_uploader.yaml``` 

1. Configurer "metadata" pour indiquer que notre version de Doctrine utilise les attributs et pas les annotations
```yaml
metadata:
  type: 'attribute'
```

2. Configurer les "mappings" pour récupérer le chemin d'enregistrement, définir le chemin racine à utiliser, 
s'assurer que le nom du fichier est unique et préserver les fichiers en cas de suppession/modification des tricks :
```yaml
mappings:
  trick_main_picture:
    uri_prefix: '%trick_main_picture%'
    upload_destination: '%kernel.project_dir%/public/%trick_main_picture%'
    namer: Vich\UploaderBundle\Naming\SmartUniqueNamer
    delete_on_update: false
    delete_on_remove: false

  trick_media:
    uri_prefix: '%trick_media%'
    upload_destination: '%kernel.project_dir%/public/%trick_media%'
    namer: Vich\UploaderBundle\Naming\SmartUniqueNamer
    delete_on_update: false
    delete_on_remove: false
```
Dans les entités qui utiliseront un upload de fichiers (Trick et Media) :
1. Activer les dépendances et VichUploader pour l'entité, exemple pour Trick
```php
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: TrickRepository::class)]
class Trick
```
2. Définir l'attibut de type "file" qui sera utilisé pour uploader puis déverser dans l'attribut de la base de données
```php
#[Vich\UploadableField(mapping: 'trick_main_picture', fileNameProperty: 'mainPicture')]
    private ?File $pictureFile = null;
```
3. Définir le getter et le setter pour cet attribut "file"
```php
public function setPictureFile(?File $mainPicture): void
    {
        $this->pictureFile = $mainPicture;

        if (null !== $mainPicture) {
            $this->createdAt = new \DateTimeImmutable();
        }
    }

    public function getPictureFile(): ?File
    {
        return $this->pictureFile;
    }
```
4. Construire le formulaire dans ```src/Form```, l'upload de fichier est géré par ```VichFileType::class```

## Génération d'un captcha

Utiliser le bundle "victor-prdh/recaptcha-bundle"
```shell
composer require victor-prdh/recaptcha-bundle
```
Répondre "n" à la première recipe (celle de google) et "y" à la 2ème (celle du bundle)

Le fichier ```.env``` est pourvu des éléments suivants, générés par le fichier ```recaptcha.yaml```
```
GOOGLE_RECAPTCHA_SITE_KEY=
GOOGLE_RECAPTCHA_SECRET_KEY=
```
Pour renseigner les 2 clés, aller sur le site https://www.google.com/recaptcha/admin et créer une nouvelle entité  
Pour snow tricks renseigner : reCAPTCHA version 2 ; Case à cocher ; domaine = localhost  
Saisir les clés générées puis intégrer le champ 'captcha' dans un formulaire


## Sécurité accès aux pages
Pour la route "new" définir l'accès dans le fichier ```config/packages/security.yaml```
```yaml
access_control:
         - { path: ^/trick/nouveau, roles: ROLE_USER }
```

Pour les routes "edit" et "delete" définir l'accès dans le contrôleur
```php
if ($trick->getUserCreator() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_trick_index', [], Response::HTTP_SEE_OTHER);
        }
```

## Mise en place pagination des messages

Installer la dépendance knp/paginator ```composer require knplabs/knp-paginator-bundle```

Dans le contrôleur Trick, utiliser les méthodes de pagination de KnpPaginatorBundle
```php
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
```
Dans la function show
```php
// Configuring paginate of messages
        $messages = $paginator->paginate(
            $messageRepository->findBy(['trick' => $trick], ['createdAt' => 'DESC']), // Request for all messages of the trick
            $request->query->getInt('page', 1), // Page number (default is 1)
            5 // Limit messages per page
        );
```
Dans le template Twig, intégrer le visuel de la pagination
```twig
{{ knp_pagination_render(messages) }}
```

## Mise en place d'un service de slugger
Installer le bundle Gedmo/Sluggable ```composer require gedmo/doctrine-extensions```

Créer le fichier ```config/packages/doctrine_extensions.yaml``` et y insérer :
```yaml
services:
  gedmo.listener.sluggable:
    class: Gedmo\Sluggable\SluggableListener
    tags:
      - { name: doctrine.event_subscriber, connection: default }
    calls:
      - [ setAnnotationReader, [ "@annotation_reader" ] ]
```
Dans l'entité qui a besoin d'être sluggable, utiliser le service de slugger
```php
use Gedmo\Mapping\Annotation as Gedmo;
```
et identifier l'attribut qui :  
1: est le slug  
2: fait référence à un attribut à slugger  
3: doit être créé en BDD
```php
#[Gedmo\Slug(fields: ['name'])]
    #[ORM\Column(type: 'string', length: 180)]
    private ?string $slug = null;
```
Créer une méthode get pour le slug
```php
public function getSlug()
    {
        return $this->slug;
    }
```
