<a id="top"></a>
# Transfer Credit Evaluation v3 (tce3)

This project is Transfer Credit Evaluation, version 3.

## About

It's a Symfony web app you can use as a starter kit for a web app that:

+ can be hosted on the LAMP stack with Plesk provided by GT Web Hosting
+ uses GT CAS for authentication
+ uses GT GTED LDAP for authorization
+ shows a form to students
+ shows options for processing the form to fac/staff
+ sends nice-looking automated emails
<br>

## Steps to create

These are the steps to create.

+ [1. Set up the app locally](#01)
+ [2. Set up local database](#02)
+ [3. Edit the local environmental variables](#03)
+ [4. Look at the app in the browser](#04)
+ [5. Create the page controller and homepage](#05)
+ [6. Set up git repo](#06)
+ [7. Add logging](#07)
+ [8. Add Symfony Profiler](#08)
+ [9. Add UID capabilities](#09)
+ [10. Add User class](#10)
+ [11. Add timestamps using DBAL](#11)
+ [12. Add data fixtures](#12)
+ [13. Add dev authentication](#13)
+ [14. Prepare the app for Apache web server](#14)
+ [15. Set up prod environment using Plesk GUI](#15)
+ [16. Add prod authentication with CAS](#16)
+ [17. Clear the cache](#17)
+ [18. Create a command](#18)
+ [19. Set up the database](#19)
+ [20. Load data](#20)
+ [21. Unload data](#21)
+ [22. Add dev authorization](#22)
+ [23. Add flash alerts](#23)
+ [24. Add prod authorization](#24)
+ [25. Add custom error page templates](#25)
+ [26. Add unit testing with PHPUnit](#26)
+ [27. Add end-to-end (e2e) testing with Codeception](#27)
+ [28. Copy in front-end project](#28)
<br>
<br>

<a id="intro"></a>

### Introduction

Mini-glossary:<br>
+ _NB = Nota bene. A latin phrase meaning "note well."_<br>
<br>

<a id="01"></a>

### 1. Set up the app locally 👷

#### 1.1 Create a new Symfony 6.3 project

_assuming Composer is already installed_

Use Composer to create a new Symfony project with a specific name provided by you.
```
composer create-project symfony/skeleton:"6.3.*" tce3
```
<br>

Then go inside the new directory
```
cd tce3
```
<br>

#### 1.2 Install Symfony CLI

Mini-glossary:<br>
+ _CLI = Command Line Interface. A means of interacting with a computer program with commands from a user and responses from the program in the form of lines of text._ (WP)<br>
<br>

_assuming curl is already installed_

Use `curl` to install the Symfony Command Line Interface (CLI).
```
curl -sS https://get.symfony.com/cli/installer | bash
```
<br>

#### 1.3 See the facts about the project

Use a console command to see basic information.
```
php bin/console about
```
<br>

#### 1.4 Ensure no security issues

Use the Symfony CLI to check for security problems.
```
symfony check:security
```
<br>

#### 1.5 Install Twig

Install the Twig templating system.
```
composer require twig
```
<br>

#### 1.6 Install MakerBundle

Install Symfony's MakerBundle.
```
composer require symfony/maker-bundle --dev
```
<br>

#### 1.7 Install Doctrine ORM (with all dependencies)

Mini-glossary:<br>
+ _ORM = Object Relational Mapping_<br>
<br>

The `-W` flag is the short version of `--with-all-dependencies`.
```
composer require orm -W
```
<br>

When asked to include Docker configuration form recipes, enter `n` (No).
<br>
<br>

#### 1.8 Install more Symfony packages

```
composer require symfony/form
```
```
composer require symfony/security-csrf
```
```
composer require symfony/validator
```
```
composer require symfony/twig-bridge
```
```
composer require annotations
```
```
composer require security
```
```
composer require orm-fixtures --dev
```
<br>

<a id="02"></a>
<a href="#top">Back to top</a>

### 2. Set up local database 🛢️
_assuming MySQL 8.0 is already installed locally_<br>

Check local version of MySQL.
```
mysql --version
```
<br>

_assuming MySQL 8.0 is running locally_<br>

Check status, start, stop, or restart.
```
sudo service mysql status
sudo service mysql start
sudo service mysql stop
sudo service mysql restart
```
<br>

Enter MySQL CLI.
```
mysql -u someoneexisting -p
```
<br>

See existing databases.
```
SHOW DATABASES;
```
<br>

See existing users.
```
SELECT * FROM mysql.user;
```
<br>

Create a new database.
```
CREATE DATABASE something_db;
```
<br>

Create a new user and give them their new password.
```
CREATE USER 'something-admin'@'localhost' IDENTIFIED WITH mysql_native_password BY 'randomcharacters';
```
<br>

Grant privileges to new user for new database.
```
GRANT ALL PRIVILEGES ON something_db.* TO 'something-admin'@'localhost' WITH GRANT OPTION;
```
<br>

Flush privileges.
```
FLUSH PRIVILEGES;
```
<br>

Exit the MySQL CLI.
```
exit
```
<br>

<a id="03"></a>
<a href="#top">Back to top</a>

### 3. Edit the local environmental variables 🌎

_assuming VS Code is already installed_

Open the project in VS Code.
```
code .
```
<br>

#### Edit the app's environmental file

Copy and edit the `.env` file.

+ Click on the `.env` file to open it in the editor.
+ Comment out line 29.
+ Uncomment line 27.
<br>
<br>

Copy the `.env` file to a new `.env.local` file.
```
cp .env .env.local
```
<br>

+ Click on the `.env.local` file to open it in the editor.
+ Edit the connection string for MySQL (but put it the actual values).
+ `DATABASE_URL="mysql://something-admin:randomcharacters@127.0.0.1:3306/something_db?serverVersion=8.0.32&charset=utf8mb4"`<br>
<br>

Verify the app has a working DBAL (Database Abstraction Layer).<br>
_There are no tables, so it will say "0 rows affected," but it will be a green OK message._
```
php bin/console dbal:run-sql 'SHOW TABLES'
```
<br>

<a id="04"></a>
<a href="#top">Back to top</a>

### 4. Look at the app in the browser 👀

Use the built-in Symfony browser. (Not for production use.)
```
symfony server:start
```
<br>

Navigate to [http://localhost:8000](http://localhost:8000).

Stop serving the app with <kbd>Ctrl</kbd> + <kbd>C</kbd>.
<br>
<br>

<a id="05"></a>
<a href="#top">Back to top</a>

### 5. Create the page controller and homepage 🏠

#### Generate the `PageController` controller using the Symfony CLI

Use the Symfony CLI to generate the `PageController` controller.
```
php bin/console make:controller PageController
```
<br>

Notice the files that were added to the app.
<br>

#### Edit the controller

Edit the file at `src/Controller/PageController.php`.
```
#[Route('/', name: 'homepage')]
public function index(): Response
{
    return $this->render('page/index.html.twig', [
        'page_title' => 'Transfer Credit Evaluation',
        'page_content' => 'The quick brown fox jumps over the lazy dog.',
    ]);
}
```
<br>

#### Edit the homepage twig template

Edit the file at `templates/page/index.html.twig`.
```
{% extends 'base.html.twig' %}

{% block site_title %}{{ page_title }} | Georgia Tech{% endblock %}

{% block title %}{{ page_title }}{% endblock %}

{% block stylesheets %}
<style>
h1 { color: purple; }
</style>
{% endblock %}

{% block heading %}
<h1>{{ page_title }}</h1>
{% endblock %}

{% block content %}
<p class="fs-5">{{ page_content }}</p>
{% endblock %}

{% block javascripts %}
<script>
var pageTitle = '{{ page_title | raw }}';
console.log(`Page titled ${pageTitle} has loaded.`);
</script>
{% endblock %}
```
<br>

#### Edit the base twig template

Edit the file at `templates/base.html.twig`.

```
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{% block site_title %}Welcome!{% endblock %}</title>
        <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🛠️</text></svg>">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
        {% block stylesheets %}
        {% endblock %}
    </head>
    <body>
        <div class="col-lg-8 mx-auto p-4 py-md-5">
            <header class="d-flex align-items-center pb-3 mb-5 border-bottom">
                {% block heading %}
                <h1 class="text-body-emphasis">Hello, world.</h1>
                {% endblock %}
            </header>
            <main>
                <div class="col-md-6">
                {% block content %}
                <p class="fs-5">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc ut aliquam tortor.</p>
                {% endblock %}
                </div>
            </main>
            <footer class="pt-5 my-5 text-body-secondary border-top">
                Made with 🍕 by barberod &middot; &copy; 2023
            </footer>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
        {% block javascripts %}
        {% endblock %}
    </body>
</html>
```
<br>

Again, look at the app in the browser.
```
symfony server:start
```
<br>

<a id="06"></a>
<a href="#top">Back to top</a>

### 6. Set up git repo 🌲
_assuming git is already installed_

Symfony has already created an appropriate `.gitignore` file for this app.
<br>

#### Tell git who you are

Set the `user.name` and `user.email` properties.
```
git config user.name "David Barbero"
```
```
git config user.email barberod@outlook.com
```
<br>

#### Make first git commit

Add everything to the repo.
```
git add --all
```
<br>

See that everything's been added.
```
git status
```
<br>

Commit everything and write a message for the commit.
```
git commit -m "initial commit"`
```
<br>

<a id="07"></a>
<a href="#top">Back to top</a>

### 7. Add logging 📓

#### Install monolog

Install the Symfony monolog bundle.
```
composer require symfony/monolog-bundle
```
<br>

#### Adjust monolog config

Edit file at `config/packages/monolog.yaml`.<br>

Ensure prod app writes logs to file. Rotate new files every day. Keep 31 days worth of files for critical-level problems.<br>

```
monolog:
    channels:
        - deprecation # Deprecations are logged in the dedicated "deprecation" channel when it exists

when@dev:
    monolog:
        handlers:
            main:
                type: stream
                path: "%kernel.logs_dir%/%kernel.environment%/debug.log"
                level: debug
                channels: ["!event"]
            # uncomment to get logging in your browser
            # you may have to allow bigger header sizes in your Web server configuration
            #firephp:
            #    type: firephp
            #    level: info
            #chromephp:
            #    type: chromephp
            #    level: info
            console:
                type: console
                process_psr_3_messages: false
                channels: ["!event", "!doctrine", "!console"]

when@test:
    monolog:
        handlers:
            dump:
                type: fingers_crossed
                action_level: error
                handler: file_log
                excluded_http_codes: [404, 405]
                channels: ["!event"]
            file_log:
                type: stream
                path: "%kernel.logs_dir%/%kernel.environment%/error_dump.log"
                level: debug

when@prod:
    monolog:
        handlers:
            main:
                type: rotating_file
                path: "%kernel.logs_dir%/%kernel.environment%/error.log"
                level: error
                filename_format: '{date}--{filename}'
                max_files: 1
            dump:
                type: fingers_crossed
                action_level: critical
                handler: file_log
            file_log:
                type: rotating_file
                path: "%kernel.logs_dir%/%kernel.environment%/critical_dump.log"
                filename_format: '{date}--{filename}'
                max_files: 31
            console:
                type: console
                process_psr_3_messages: false
                channels: ["!event", "!doctrine"]
            deprecation:
                type: rotating_file
                channels: [deprecation]
                path: "%kernel.logs_dir%/%kernel.environment%/deprecation.log"
                filename_format: '{date}--{filename}'
                max_files: 1
            syslog_handler:
                type: syslog
                level: error

```
<br>

See the logging in action by adding something to the page controller and then loading the homepage in the browser.<br>

```
#[Route('/', name: 'homepage')]
public function index(LoggerInterface $logger): Response
{
    $logger->info('This proves logging works.');

    return $this->render('page/index.html.twig', [
        'page_title' => 'Transfer Credit Evaluation',
        'page_content' => 'The quick brown fox jumps over the lazy dog.',
    ]);
}
```
<br>

<a id="08"></a>
<a href="#top">Back to top</a>

### 8. Add Symfony Profiler

#### Install the profiler package
```
composer require --dev symfony/profiler-pack
```
<br>

Now, look at the app in the browser. You'll see a very helpful toolbar. Click on the various icons to see whole pages of info about the app.
```
symfony server:start
```
<br>

<a id="09"></a>
<a href="#top">Back to top</a>

### 9. Add UID capabilities

Mini-glossary:<br>
+ _uid = unique identifier_<br>
+ _uuid = universally unique identifier_<br>
<br>

Install the uid package.
```
composer require symfony/uid
```
<br>

<a id="10"></a>
<a href="#top">Back to top</a>

### 10. Add User clas

The security bundle should already be installed.<br>
If, for some strange reason, it isn't...
```
composer require symfony/security-bundle
```
<br>

Create the `User` class.
```
php bin/console make:user
```
<br>

Answer the prompts with these values.
```
# name of security user class
User

# store user in database via Doctrine
yes

# property that will be the unique display name
username

# Does app need to hash passwords?
no
```
<br>

Add more properities to the `User` class.<br>
The naming conventions for PHP variables in Symfony is camelCase. See [Coding Standards](https://symfony.com/doc/current/contributing/code/standards.html).

Edit the file at `src/Entity/User.php`.
```
#[ORM\Id]
#[ORM\Column(type: UuidType::NAME, unique: true)]
#[ORM\GeneratedValue(strategy: 'CUSTOM')]
#[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
private ?Uuid $id = null;

#[ORM\Column(length: 180, unique: true)]
private ?string $username = null;

#[ORM\Column]
private ?int $orgID = null;

#[ORM\Column(length: 255)]
private ?string $displayName = null;

#[ORM\Column(length: 180)]
private ?string $email = null;

#[ORM\Column(length: 24)]
private ?string $category = 'member';

#[ORM\Column]
private ?int $status = 1;

#[ORM\Column]
private ?int $frozen = 0;

#[ORM\Column(length: 48)]
private ?string $loadedFrom = null;
```

_NB: Leave `$roles` as it is._
<br>

Now create the getters and setters.<br>
For other entities, it should be possible to have the MakerBundle generate the getters and setters,
but that doesn't seem to be an option for the `User` security class.
```
public function getId(): ?Uuid
{
    return $this->id;
}

public function getUsername(): ?string
{
    return $this->username;
}

public function setUsername(string $username): static
{
    $this->username = $username;

    return $this;
}

public function getOrgID(): ?int
{
    return $this->orgID;
}

public function setOrgID(int $orgID): static
{
    $this->orgID = $orgID;

    return $this;
}

public function getDisplayName(): ?string
{
    return $this->displayName;
}

public function setDisplayName(string $displayName): static
{
    $this->displayName = $displayName;

    return $this;
}

public function getEmail(): ?string
{
    return $this->email;
}

public function setEmail(string $email): static
{
    $this->email = $email;

    return $this;
}

public function getCategory(): ?string
{
    return $this->category;
}

public function setCategory(string $category): static
{
    $this->category = $category;

    return $this;
}

public function getStatus(): ?int
{
    return $this->status;
}

public function setStatus(int $status): static
{
    $this->status = $status;

    return $this;
}

public function getFrozen(): ?int
{
    return $this->frozen;
}

public function setFrozen(int $frozen): static
{
    $this->frozen = $frozen;

    return $this;
}

public function getLoadedFrom(): ?string
{
    return $this->loadedFrom;
}

public function setLoadedFrom(string $loadedFrom): static
{
    $this->loadedFrom = $loadedFrom;

    return $this;
}
```

_NB: Leave `getRoles()` and `setRoles()` as they are._
<br>
<br>

<a id="11"></a>
<a href="#top">Back to top</a>

### 11. Add timestamps using DBAL

Mini-glossary:<br>
+ _DBAL = Database Abstraction Layer_<br>
<br>

Prepare to add `created` and `updated` properties:

Install the Doctrine extensions bundle.
```
composer require stof/doctrine-extensions-bundle
```
```
# This recipe comes from the community... Do you want to execute it?
[y] Yes
```
<br>

Enable `Timestampable`.<br>
Edit the file at `config/packages/stof_doctrine_extensions.yaml`.
```
stof_doctrine_extensions:
    default_locale: en_US
    orm:
        default:
            timestampable: true
```
<br>

Edit the `User` class.<br>
Inject `DateTime`, `Gedmo`, `UuidType`, and `Uuid`.
```
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Security\Core\User\UserInterface;
```
<br>

Add the `created` and `updated` properties.<br>
```
#[Gedmo\Timestampable(on: 'create')]
#[ORM\Column]
private ?DateTime $created;

#[Gedmo\Timestampable(on: 'change', field: ['orgID', 'displayName', 'email', 'category', 'status', 'frozen'])]
#[ORM\Column]
private ?DateTime $updated;
```
<br>

Add the `created` and `updated` getters.<br>
Setters are not neeeded because the Doctrine extension will do the setting.
```
public function getCreated()
{
    return $this->created;
}

public function getUpdated()
{
    return $this->updated;
}
```
<br>

Create a migration and inspect the `CREATE` statement.<br>
```
symfony console make:migration
```
<br>

Why `symfony console` instead of `php bin/console`?<br>
The latter never uses the symfony binary, so it never has a changed to add the app's environmental variables.<br>
The former is used whenever the values set in the `.env` file are needed.<br>
<br>

The generated migration file is found in the migrations folder (Version + time stamp).<br>
<br>

Migrate (i.e., generate the app's database)
```
symfony console doctrine:migrations:migrate
```
```
# Could be scheme changes and data loss. Continue?
yes
```
<br>

If all goes as expected, you'll get a green OK message saying "Successfully migrated to version..."

Then you can also use a database explorer client (e.g., MySQL Workbench, VS Code MySQL extension) to see the user table properly made.
<br>
<br>

<a id="12"></a>
<a href="#top">Back to top</a>

### 12. Add data fixtures

Add constants for roles in the `User` class.

We can use constants for roles to find usages all over the application rather than doing a full-text search on the "ROLE_" string. It also prevents from making typo errors.

Edit the file at `src/Entity/User.php`, adding constants inside the `User` class above the other variable declarations. (Line 14)
```
final public const ROLE_USER = 'ROLE_USER';
final public const ROLE_UGAPP = 'ROLE_UGAPP';
final public const ROLE_GSAPP = 'ROLE_GSAPP';
final public const ROLE_STUDENT = 'ROLE_STUDENT';
final public const ROLE_STAFF = 'ROLE_STAFF';
final public const ROLE_FACULTY = 'ROLE_FACULTY';
final public const ROLE_ADMIN = 'ROLE_ADMIN';
```
<br>

Edit the `getRoles()` function accordingly.
```
public function getRoles(): array
/**
 * @see UserInterface
 */
public function getRoles(): array
{
    $roles = $this->roles;

    // guarantees that a user always has at least one role for security
    if (empty($roles)) {
        $roles[] = self::ROLE_USER;
    }

    return array_unique($roles);
}
```
<br>

The Doctrine Fixtures bundles should already be installed.

Edit the file at `src/DataFixtures/AppFixtures.php`.

Add the `User` class with dependency injection.
```
use App\Entity\User;
```
<br>

Add the `getUserData()` function.
```
/**
 * @return array<array{string, int, string, string, string, int, int, array<string>}>
 */
private function getUserData(): array
{
    return [
        // $userData = [$username, $orgID, $displayName, $email, $category, $status, $frozen, $roles];
        ['burnhamm','100000001','Michael Burnham','michael.burnham@example.edu','staff',1,1,[User::ROLE_ADMIN]],
        ['janewayk','100000002','Kathryn Janeway','kathryn.janeway@example.edu','faculty',1,1,[User::ROLE_FACULTY]],
        ['uhuran','100000003','Nyota Uhura','nyota.uhura@example.edu','staff',1,1,[User::ROLE_STAFF]],
        ['crusherb','100000004','Beverly Crusher','beverly.crusher@example.edu','student',1,1,[User::ROLE_STUDENT]],
        ['daxj','100000005','Jadzia Dax','jadzia.dax@example.edu','applicant',1,1,[User::ROLE_GSAPP]],
        ['satoh','100000006','Hoshi Sato','hoshi.sato@example.edu','applicant',1,1,[User::ROLE_UGAPP]],
        ['hansena','100000007','Annika Hansen','annika.hansen@example.edu','member',1,1,[User::ROLE_USER]],
        ['marinerb','100000008','Beckett Mariner','beckett.mariner@example.edu','faculty',1,1,[User::ROLE_FACULTY,User::ROLE_STAFF]],
        ['ortegase','100000009','Erica Ortegas','eria.ortegas@example.edu','student',1,1,[User::ROLE_STUDENT,User::ROLE_GSAPP]],
        ['kes','100000010','Kes','kes@example.edu','member',0,1,[User::ROLE_STAFF]]
    ];
}
```
<br>

Add the `loadUsers()` function.<br>
(The demo app puts it above `getUserData()` in the `AppFixtures` class, but it technically doesn't matter what order.)
```
private function loadUsers(ObjectManager $manager): void
{
    foreach ($this->getUserData() as [$username, $orgID, $displayName, $email, $category, $status, $frozen, $roles]) {
        $user = new User();
        $user->setUsername($username);
        $user->setOrgID($orgID);
        $user->setDisplayName($displayName);
        $user->setEmail($email);
        $user->setCategory($category);
        $user->setStatus($status);
        $user->setFrozen($frozen);
        $user->setRoles($roles);
        $manager->persist($user);
        $this->addReference($username, $user);
    }

    $manager->flush();
}
```
<br>

Rewrite the `load()` function with `loadUsers()`.
```
public function load(ObjectManager $manager): void
{
    $this->loadUsers($manager);
}
```
<br>

<a id="13"></a>
<a href="#top">Back to top</a>

### 13. Add dev authentication

Mini-glossary:<br>
+ _YAML = YAML Ain't Markup Language™. YAML is a human-friendly data serialization language for all programming languages._<br>
<br>

_NB: When working with YAML files identation and spacing can be make-or-break!_

Edit the file at `config/packages/security.yaml`.

Since it seems that configurations cannot be partially overwritten, we'll put the whole security block (except for what's already in a `when@test` block) into a `when@prod` block.

We'll also take this opportunity to define some access control and role hierarchy.
```
when@prod:    
    security:
        providers:
            app_user_provider:
                entity:
                    class: App\Entity\User
                    property: username
        firewalls:
            dev:
                pattern: ^/(_(profiler|wdt)|css|images|js)/
                security: false
            main:
                lazy: true
                provider: app_user_provider
        access_control:
            - { path: ^/my, roles: ROLE_USER }
        role_hierarchy:
            ROLE_ADMIN: [ROLE_FACULTY,ROLE_STAFF,ROLE_STUDENT,ROLE_GSAPP,ROLE_UGAPP,ROLE_USER]
            ROLE_FACULTY: ROLE_USER
            ROLE_STAFF: ROLE_USER
            ROLE_STUDENT: ROLE_USER
            ROLE_GSAPP: ROLE_USER 
            ROLE_UGAPP: ROLE_USER
```
<br>

Define basic credentials to use when logging into the dev environment.

Add to the `.env.local` file.
```
###> http_basic login credentials ###
# Seriously, this is just for the dev environment!! #
HTTP_BASIC_AUTH_USERNAME=someusernamebutnotthis
HTTP_BASIC_AUTH_PASSWORD=somepasswordbutnotthis
###< http_basic login credentials ###
```
<br>

Add a `when@dev` block below everything that's already in the YAML file.
```
when@dev:
    security:
        password_hashers:
            Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface: plaintext
        providers:
            in_memory_users:
                memory:
                    users:
                        - identifier: '%env(HTTP_BASIC_AUTH_USERNAME)%' 
                          password: '%env(HTTP_BASIC_AUTH_PASSWORD)%'
                          roles: [ ROLE_ADMIN ]
        firewalls:
            main:
                pattern: ^/
                http_basic: ~
                provider: in_memory_users
        access_control:
            - { path: ^/my, roles: ROLE_USER }
        role_hierarchy:
            ROLE_ADMIN: [ROLE_FACULTY,ROLE_STAFF,ROLE_STUDENT,ROLE_GSAPP,ROLE_UGAPP,ROLE_USER]
            ROLE_FACULTY: ROLE_USER
            ROLE_STAFF: ROLE_USER
            ROLE_STUDENT: ROLE_USER
            ROLE_GSAPP: ROLE_USER 
            ROLE_UGAPP: ROLE_USER
```
<br>

Add the `/my` route. Add functions to `PageController`.
```
#[Route('/my', name: 'secure_page')]
    public function secure(LoggerInterface $logger): Response
    {
        return $this->render('page/index.html.twig', [
            'page_title' => 'Secure Page',
            'page_content' => 'This is a secure page.',
        ]);
    }
```
<br>

Attempt to view the app in your web browser. Navigate to `/my`.

When prompted by your web browser, enter the username and password you defined in the `.env.local` file.

_NB: While setting up authorization, use private or incognito browser windows to ensure fresh cookies while inspecting different functionalities._
```
symfony server:start
```
<br>

Notice that user info can be now seen in the profiler toolbar.
<br>
<br>

<a id="14"></a>
<a href="#top">Back to top</a>

### 14. Prepare the app for Apache web server with PHP-FPM

Mini-glossary:<br>
+ _FPM = FastCGI Process Manager_<br>
+ _FastCGI = FastCGI is a binary protocol for interfacing interactive programs with a web server. Instead of creating a new process for each request, FastCGI uses persistent processes to handle a series of requests. These processes are owned by the FastCGI server, not the web server._ (Wikipedia)<br>
+ _CGI = Common Gateway Interface_<br>
<br>

Install the apache Symfony package
```
composer require symfony/apache-pack
```
<br>

Adjust the apache config by editing the file at `public/.htaccess`.<br>
Most of the configuration is handled by OIT's team. Developers don't have too much control.<br>
The Symfony documentation assumes you maintain the server yourself. When you are maintaining the server yourself, follow the Symfony documentation.
When using GT Web Hosting, just add this to `public/.htaccess`.
```
Require all granted
FallbackResource /index.php
```
<br>

<a id="15"></a>
<a href="#top">Back to top</a>

### 15. Set up prod environment using Plesk GUI

Mini-glossary:<br>
+ _GUI = Graphical User Interface. A form of user interface that allows a user to interact with a program through graphical icons, windows, and menus._ (WP)<br>
<br>

_Assuming prod environment exists, is available, and meets requirements._

Be sure you are connected to the Institute's VPN.

Go to the site's Plesk control panel via https://hosting.gatech.edu.
<br>
<br>

Inspect the site settings and file directories.
+ Make sure the document root is `/httpdocs` initially.
+ Make sure `/httpdocs` directory exists and is empty.
+ Make sure the PHP version is what the site needs and expects. In this case presently that's *8.1.21*.
+ Make sure the PHP-FPM configuration is what is needed. In this case presently that's *Dedicated FPM application served by Apache*.
+ Make sure the site has no databases yet because it shouldn't.
+ Make sure the site has no connection to a Git repo yet because it shouldn't.
<br>
<br>

🔄 Click on "Websites & Domains" in the left sidebar to return to the Plesk console homepage for this site.

Click on "Add Database."
+ Enter an appropriate database name. `tce3_db`
+ Take note of the database version. `MariaDB v10.6.14`
+ If unchecked, do check the "Create a database user" checkbox.
+ Enter an appropriate database user name. `tce3`
+ Generate a "very strong" password. You can use the "Generate" button and add to it until it's very strong, or you can use another generator and cut-and-paste. Note this password in a safe place. `somestrongpasswordbutnothis` (no really, not that)
<br>
<br>

🔄 Click on "Websites & Domains" in the left sidebar to return to the Plesk console homepage for this site.

Click on "Git."
+ Click on "Add Repository."
+ Make sure "Remote repository" is selected. It should be the default.
+ Enter the Repository URL. Use the SSH protocol option for it. (You probably need to navigate to GitHub and look under the green "<>Code" dropdown button. `git@github.com:barberod/tce3.git`.
+ When you paste in a valid value using SSH protocol, the Plesk GUI will automatically show different fields.
+ SSH public key creation remains `Default`.
+ SSH public key content -- Copy everything in the box including "ssh-rsa" at the start and "==" at the end... Leaving the Plesk tab/window open, again go back to this repo on GitHub. Then go to Settings. Then select "Deploy keys" under the Security subheading in the left sidebar... Then click "Add deploy key" on the right side of the page... Paste in the value to the Key field. In the Title field, enter the name of the server (e.g., `web-plesk55`). No need to allow write access. Click the green "Add key" button. (MFA will be needed now.)
+ Return to the Plesk tab/window that you had left open. Select the "Manual" deployment setting.
+ Make sure the server path is `/httpdocs`.
+ No additional deployment actions to enable.
+ Click the blue "Create" button.
<br>

Plesk will pull the repo onto the site. Then it will show you a page will more info about the repo being connected. Under the Deployment heading, click on "Deploy now."
<br>
<br>

🔄 Click on "Websites & Domains" in the left sidebar to return to the Plesk console homepage for this site.

Click on "File Manager."
+ View the httpdocs directory.
+ View the contents of the `.env` file. Copy the full contents of the `.env` file.
+ Return to viewing the httpdocs directory.
+ Use the blue plus sign button to create a new file in the httpdocs directory.
+ Give it the file name of `.env.local`. (Do not use the HTML template.) Click OK.
+ Now click to view the (empty) contents of `.env.local` and paste in what you had previously copied from `.env`.
+ On line 18, make sure it is `APP_ENV=prod`.
+ Paste in the correct values for the MariaDB connection.
+ For example `DATABASE_URL="mysql://tce3:randomcharacters@127.0.0.1:3306/tce3_db?serverVersion=10.6.14-MariaDB&charset=utf8mb4"`
+ Click the blue "Save" button.
<br>
<br>

🔄 Click on "Websites & Domains" in the left sidebar to return to the Plesk console homepage for this site.

Click on "PHP Composer."
+ If you get the alert "Information about the packages is not up-to-date. Click 'refresh' to update it" then do click on "Refresh" now.
+ Under the "Package Dependencies" heading, click on the gray "Update" button.
+ Any alerts or problems that arise from Composer will need to be addressed on a case-by-case basis.

_NB: The point of Composer is to keep track of your app's various packages and make sure they can coexist._
<br>
<br>

🔄 Click on "Websites & Domains" in the left sidebar to return to the Plesk console homepage for this site.

Click on "Hosting."
+ Set the document root to `/httpdocs/public`.

_NB: Yes, the site will server from `/httpdocs/public` but the Git repo will deploy `/httpdocs`._
<br>
<br>

Now, navigate to the site where it is on the internet (e.g., https://something-dev.registrar.gatech.edu) and see the homepage served up.

You still shouldn't be able to access anything at `/my`.
<br>
<br>

<a id="16"></a>
<a href="#top">Back to top</a>

### 16. Add prod authentication with CAS

Mini-glossary:<br>
+ _CAS = Central Authentication Service_<br>
<br>

Install monolog (Should already have it.)
```
composer require monolog/monolog.
```
<br>

Install `symfony/cache`.
```
composer require symfony/cache
```
<br>

Install http factory.
```
composer require nyholm/psr7
```
<br>

Install the Symfony http client.
```
composer require symfony/http-client
```
<br>

Install the `ecphp/cas-bundle` package.
```
composer require ecphp/cas-bundle

# Contributed repo... Ok to execute recipe?
[y] Yes
```
<br>

Now that `ecphp/cas-bundle` is installed, make sure the bundle exists in `config/bundles.php`.
```
EcPhp\CasBundle\CasBundle::class => ['all' => true],
```
<br>

Add values for the environmental variables in the prod `.env.local` file, by doing the following.

+ Be sure you are connected to the Institute's VPN.
+ Go to the site's Plesk control panel via https://hosting.gatech.edu
+ View the httpdocs directory
+ Click on `.env.local` and the Plesk GUI's code editor will fly out.
+ Append the following or, if it's already present, overwrite the values if necessary. When finished, click the blue "Save" button.
<br>

```
###> ecphp/cas-bundle ###
CAS_HOST=https://sso.gatech.edu
CAS_PATH=/cas
CAS_PORT=443
CAS_LOGIN_TARGET=https://the-prod-env-domain.gatech.edu/my
CAS_LOGOUT_TARGET=https://the-prod-env-domain.gatech.edu
###< ecphp/cas-bundle ###
```
<br>

And add the parameters in your `config/services.yml` file under `parameters:`.
```
parameters:
    cas_login_target: '%env(string:CAS_LOGIN_TARGET)%'
    cas_logout_target: '%env(string:CAS_LOGOUT_TARGET)%'
    cas_host: '%env(string:CAS_HOST)%'
    cas_port: '%env(int:CAS_PORT)%'
    cas_path: '%env(string:CAS_PATH)%'

```
<br>

Create `UserProvider` class at `src/Security/UserProvider.php`.<br>
You'll likely be creating the `src/Security` directory now.
```
/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ecphp
 */

declare(strict_types=1);

namespace App\Security;

use EcPhp\CasBundle\Security\Core\User\CasUser;
use EcPhp\CasBundle\Security\Core\User\CasUserInterface;
use EcPhp\CasBundle\Security\Core\User\CasUserProviderInterface;
use EcPhp\CasLib\Introspection\Contract\IntrospectorInterface;
use EcPhp\CasLib\Introspection\Contract\ServiceValidate;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;

use function get_class;

final class UserProvider implements CasUserProviderInterface
{
    private IntrospectorInterface $introspector;
    private LoggerInterface $logger;

    public function __construct(
        IntrospectorInterface $introspector,
        LoggerInterface $logger
    ) {
        $this->introspector = $introspector;
        $this->logger = $logger;
    }

    public function loadUserByIdentifier($identifier): UserInterface
    {
        throw new UnsupportedUserException('Unsupported operation.');
    }

    public function loadUserByResponse(ResponseInterface $response): CasUserInterface
    {
        try {
            $introspect = $this->introspector->detect($response);
        } catch (InvalidArgumentException $exception) {
            throw new AuthenticationException($exception->getMessage());
        }

        if ($introspect instanceof ServiceValidate) {
            return new CasUser($introspect->getCredentials());
        }

        throw new AuthenticationException('Unable to load user from response.');
    }

    public function loadUserByUsername(string $username): UserInterface
    {
        throw new UnsupportedUserException(sprintf('Username "%s" does not exist.', $username));
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof CasUserInterface) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }
        $this->logger->debug('refreshUser() user = '.$user);
        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return CasUser::class === $class;
    }

}

```
<br>

Edit `config/packages/security.yaml`.
```
when@prod:    
    security:
        providers:
            app_sec_userprovider:
                entity:
                    class: App\Security\UserProvider
                    property: username
        firewalls:
            dev:
                pattern: ^/(_(profiler|wdt)|css|images|js)/
                security: false
            secured:
                provider: cas
                pattern: ^/my
                custom_authenticator: EcPhp\CasBundle\Security\CasAuthenticator
                form_login:
                    check_path: cas_bundle_login
                    login_path: cas_bundle_login
                entry_point: EcPhp\CasBundle\Security\CasAuthenticator
            main:
                provider: app_sec_userprovider
        access_control:
            - { path: ^/my, roles: ROLE_CAS_AUTHENTICATED }
            - { path: ^/admin, roles: ROLE_ADMIN }
        role_hierarchy:
            ROLE_ADMIN: [ROLE_FACULTY,ROLE_STAFF,ROLE_STUDENT,ROLE_GSAPP,ROLE_UGAPP,ROLE_USER]
            ROLE_FACULTY: ROLE_USER
            ROLE_STAFF: ROLE_USER
            ROLE_STUDENT: ROLE_USER
            ROLE_GSAPP: ROLE_USER 
            ROLE_UGAPP: ROLE_USER
            ROLE_USER: ROLE_CAS_AUTHENTICATED

```
<br>

Edit `config/packages/prod/cas_security.yaml` to include the custom `UserProvider` class in the security configuation.
```
security:
    providers:
        cas:
            id: App\Security\UserProvider

```
<br>

Create CAS routes in `PageController`. Edit `src/Controller/PageController.php` to be the following.
```
<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        return $this->render('page/index.html.twig', [
            'page_title' => 'Transfer Credit Evaluation',
            'page_content' => 'The quick brown fox jumps over the lazy dog.',
        ]);
    }

    #[Route('/my', name: 'secure_page')]
    public function secure(): Response
    {
        dump($this->container->get('security.token_storage'));
        dump($this->getUser());

        return $this->render('page/index.html.twig', [
            'page_title' => 'Secure Page',
            'page_content' => 'This is a secure page.',
        ]);
    }

    #[Route('/login', name: 'login')]
    public function login(): Response
    {
        $target = urlencode($this->getParameter('cas_login_target'));
        $url = 'https://'.$this->getParameter('cas_host') . ((($this->getParameter('cas_port')!=80) || ($this->getParameter('cas_port')!=443)) ? ":".$this->getParameter('cas_port') : "") . $this->getParameter('cas_path') . '/login?service=';
        return $this->redirect($url . $target);
    }
 
    #[Route('/logout', name: 'logout')]
    public function logout(): Response
    {
        session_destroy();
        $target = urlencode($this->getParameter('cas_logout_target'));
        $url = 'https://'.$this->getParameter('cas_host') . ((($this->getParameter('cas_port')!=80) || ($this->getParameter('cas_port')!=443)) ? ":".$this->getParameter('cas_port') : "") . $this->getParameter('cas_path') . '/logout?service=';
        return $this->redirect($url . $target);
    }
}

```
<br>

Tell CAS to use environmental variables. Edit the file at `config/packages/prod/cas_bundle.yaml` to be the following.

_NB: When working with YAML files identation and spacing can be make-or-break!_
```
cas:
    base_url: '%env(CAS_HOST)%:%env(CAS_PORT)%%env(CAS_PATH)%'
    protocol:
        login:
            path: /login
            allowed_parameters:
                - service
                - renew
                - gateway
            default_parameters:
                service: '%env(CAS_LOGIN_TARGET)%'
        serviceValidate:
            path: /p3/serviceValidate
            allowed_parameters:
                - service
                - pgtUrl
                - renew
                - format
            default_parameters:
                format: JSON
                #pgtUrl: cas_bundle_proxy_callback
        logout:
            path: /logout
            allowed_parameters:
                - service
            default_parameters:
                service: '%env(CAS_LOGOUT_TARGET)%'
        proxy:
            path: /proxy
            allowed_parameters:
                - targetService
                - pgt
        proxyValidate:
            path: /p3/proxyValidate
            allowed_parameters:
                - service
                - ticket
                - pgtUrl
                - format
            default_parameters:
                format: JSON
                #pgtUrl: cas_bundle_proxy_callback

```
<br>

Now, commit all these changes and push to the main branch of the repo.
<br>
<br>

Pull in the latest changes using the Plesk GUI.

Be sure you are connected to the Institute's VPN.

Go to the site's Plesk control panel via https://hosting.gatech.edu.

Click on "Git."
+ Click on "Pull Now."
+ Plesk will pull the repo onto the site. Then it will show you a page will more info about the repo being connected. 
+ Under the Deployment heading, click on "Deploy now."
<br>
<br>

🔄 Click on "Websites & Domains" in the left sidebar to return to the Plesk console homepage for this site.

Click on "PHP Composer"
+ If you get the alert "Information about the packages is not up-to-date. Click 'refresh' to update it" then do click on "Refresh" now.
+ Under the "Package Dependencies" heading, click on the gray "Update" button.
+ Any alerts or problems that arise from Composer will need to be addressed on a case-by-case basis.

_NB: The point of Composer is to keep track of your app's various packages and make sure they can coexist._
<br>
<br>

Open a new private or incognito browser window. Attempt to navigate to page at the `/my` route.
You should be routed through the Single Sign-On (SSO) system.
After successfully logging in, you should see the page at the `/my` route, which presently shows a dumps of `security.token_storage` and the current user.
<br>

Finally, informally test the `/logout` route and the `/login` route.
<br>

Once you're sure that CAS is being used successfully, go to the `PageController` and comment out the uses of the `dump()` function, which will cause errors for upcoming steps in the development process.

Edit the file at `src/Controller/PageController.php` using double slashes (`//`) to comment out code.
```
// dump($this->container->get('security.token_storage'));
// dump($this->getUser());
```
<br>

<a id="17"></a>
<a href="#top">Back to top</a>

### 17. Clear the cache

There is a command to clear Symfony's cache. Clear the cache as often as is needed or desired.

Clear the cache.
```
php bin/console cache:clear
```
<br>

But, in the prod environment your options are limited. If you are SSHed in, you can force remove recursively all contents of the cache dir.
```
# You know this is dangerous. Be careful.
rm -rf httpdocs/var/cache/prod/*
```
<br>

Otherwise, you can go to the Plesk GUI and delete the dir at `var/cache/prod`.
<br>
<br>

<a id="18"></a>
<a href="#top">Back to top</a>

### 18. Create a command

Use Symfony's MakerBundle to make commands that can be used in the command line or with Composer's `install` and/or `update` processes.

Create an example command.
```
php bin/console make:command example --env=dev
```
<br>

Create various options within the `example` command.<br>
Edit the file at `src/Command/ExampleCommand.php`.
```
<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'tce3:example',
    description: 'An example of a command',
    hidden: false,
    aliases: ['tce3:run-example','tce3:show-example']
)]
class ExampleCommand extends Command
{
    protected function configure(): void
    {
        $this
            // the command help shown when running the command with the "--help" option
            ->setHelp('This is an example of a command.')

            // configure an argument
            ->addArgument('first_name', InputArgument::REQUIRED, 'Provide your first name')
            ->addArgument('double_check', InputArgument::REQUIRED, 'If you want to run the script, type "YES" (case sensitive)')
            ->addArgument('progress_bar_size', InputArgument::OPTIONAL, 'Indicate the size of the progress bar to demo')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $firstName = $input->getArgument('first_name');
        $doubleCheck = $input->getArgument('double_check');
        $progressBarSize = $input->getArgument('progress_bar_size');
        
        $io = new SymfonyStyle($input, $output);

        $io->title('Example Command');

        $io->section('Handling Input');
        $io->text('Hello, '.$firstName.'.');
        if ($doubleCheck === 'YES') {
            $io->text('Because you typed "YES" the script will run.');
        } else {
            $io->text('The script will not run because you did not type "YES" (case sensitive).');
        }
        if ($progressBarSize) {
            $io->text('You will see a progress bar of size '.$progressBarSize.'.');
        } else {
            $io->text('You will see no progress bar.');
        }

        $io->section('Demonstrating the progress bar');
        if ($progressBarSize) {
            if (!is_numeric($progressBarSize)) {
                throw new \RuntimeException('The size of the progress bar must be a valid number.');
            }

            $this->demoProgressBar($io, (int)$progressBarSize);
            $io->note('You saw a progress bar of size '.$progressBarSize.'.');
        } else {
            $io->warning('Declined.');
            $io->note('You opted not to see a progress bar.');
        }

        $io->section('Optional demonstrations');
        $this->demoListing($io);
        $this->demoTable($io);
        $this->demoHorizontalTable($io);
        $this->demoDefinitionList($io);

        $io->section('Running a PHP script');
        if ($doubleCheck === 'YES') {
            $scriptOutput = null;
            $returnValue = null;
            exec('php bin/console about -n', $scriptOutput, $returnValue);

            if ($returnValue === 0) {
                $io->success('Success!');
                $io->text($scriptOutput);
                return Command::SUCCESS;
            } else {
                $io->error('Failure.');
                $io->text($scriptOutput);
                return Command::FAILURE;
            }
        } else {
            $io->warning('Declined.');
            $io->text('You opted not to run the script.');
            $io->newLine();
            return Command::INVALID;
        }
    }

    function demoListing(SymfonyStyle $io) {
        $userListing = $io->confirm('Do you want to see a demo of a listing?', false);
        if ($userListing) {
            $io->section('Demonstrating a listing');
            $io->listing([
                'Element #1 Lorem ipsum dolor sit amet',
                'Element #2 Lorem ipsum dolor sit amet',
                'Element #3 Lorem ipsum dolor sit amet',
            ]);
        }
    }

    function demoTable(SymfonyStyle $io) {
        $userTable = $io->confirm('Do you want to see a demo of a table?', false);
        if ($userTable) {
            $io->section('Demonstrating a table');
            $io->table(
                ['Header 1', 'Header 2'],
                [
                    ['Cell 1-1', 'Cell 1-2'],
                    ['Cell 2-1', 'Cell 2-2'],
                    ['Cell 3-1', 'Cell 3-2'],
                ]
            );
        }
    }

    function demoHorizontalTable(SymfonyStyle $io) {
        $userHorizontalTable = $io->confirm('Do you want to see a demo of a horizontal table?', false);
        if ($userHorizontalTable) {
            $io->section('Demonstrating a horizontal table');
            $io->horizontalTable(
                ['Header 1', 'Header 2'],
                [
                    ['Cell 1-1', 'Cell 1-2'],
                    ['Cell 2-1', 'Cell 2-2'],
                    ['Cell 3-1', 'Cell 3-2'],
                ]
            );
        }
    }

    function demoDefinitionList(SymfonyStyle $io) {
        $userDefinitionList = $io->confirm('Do you want to see a demo of a definition list?', false);
        if ($userDefinitionList) {
            $io->section('Demonstrating a definition list');
            $io->definitionList(
                'This is a title',
                ['foo1' => 'bar1'],
                ['foo2' => 'bar2'],
                ['foo3' => 'bar3']
            );
        }
    }

    function demoProgressBar(SymfonyStyle $io, int $numberOfSteps) {
        foreach ($io->progressIterate($this->generateIterable($numberOfSteps)) as $value) {
            sleep(1);
            $io->text('Step:'.$value.'!');
            $io->newLine();
        }
    }

    function generateIterable(int $numberOfItems): array {
        $iterable = [];
        for ($i = 1; $i <= $numberOfItems; $i++) {
            $iterable[] = $i;
        }
        return $iterable;
    }
}

```
<br>

<a id="19"></a>
<a href="#top">Back to top</a>

### 19. Set up the database

Create an `DatabaseSetup` command.
```
php bin/console make:command database-setup
```
<br>

Edit the file at `src/Command/DatabaseSetupCommand.php`.
```
<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'tce3:database-setup',
    description: "This command can be added to the composer.json file so it can run Composer's install and/or update processes.",
    hidden: false,
    aliases: ['tce3:setup-database']
)]
class DatabaseSetupCommand extends Command
{
    protected function configure(): void
    {
        $this
        ->addArgument('double_check', InputArgument::REQUIRED, 'If you want to run the script, type "Risk-Accepted". To intentionally bypass, type "bypass". (case sensitive)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $doubleCheck = $input->getArgument('double_check');

        if ($doubleCheck === 'Risk-Accepted') {

            if ($this->dumpExistingDatabaseToFile($io) != 0) {
                $io->error('Failure.');
                return Command::FAILURE;
            }

            if ($this->createDatabase($io) != 0) {
                $io->error('Failure.');
                return Command::FAILURE;
            }

            if ($this->migrateDatabase($io) != 0) {
                $io->error('Failure.');
                return Command::FAILURE;
            }

            $io->success('Success!');
            return Command::SUCCESS;
        
        } else if ($doubleCheck === 'bypass') {
            $io->success('Successfully bypassed!');
            return Command::SUCCESS;

        } else {
            $io->note('By not adding the "Risk-Accepted" argument, you declined to run the script.');
            $io->warning('Declined.');
            $io->newLine();
            return Command::INVALID;
        }

    }

    function dumpExistingDatabaseToFile(SymfonyStyle $io) {
        if (!file_exists('data/sql/backups')) {
            mkdir('data/sql/backups', 0777, true);
        };

        $scriptOutput = null;
        $returnValue = null;

        $io->section('Dumping the existing database to a sql file');
        $io->text('Executing the PHP script:');
        $io->text('mysqldump --no-tablespaces -u user -ppword schema > filename.sql');
        $io->newLine();

        $ts = date("Ymdhis");
        exec("mysqldump --no-tablespaces -u {$_ENV['DB_USERNAME']} -p{$_ENV['DB_PASSWORD']} {$_ENV['DB_SCHEMA']} > data/sql/backups/{$_ENV['DB_SCHEMA']}_{$ts}.sql", $scriptOutput, $returnValue);

        $io->text('The PHP script returned:');
        $io->text($scriptOutput);

        return $returnValue;
    }

    function createDatabase(SymfonyStyle $io) {
        $scriptOutput = null;
        $returnValue = null;

        $io->section('Creating the database');
        $io->text('Executing the PHP script:');
        $io->text('php bin/console doctrine:database:create --env='.$_ENV["APP_ENV"].' --if-not-exists');
        $io->newLine();

        exec('php bin/console doctrine:database:create --env='.$_ENV["APP_ENV"].' --if-not-exists', $scriptOutput, $returnValue);

        $io->text('The PHP script returned:');
        $io->text($scriptOutput);
        $io->newLine();

        return $returnValue;
    }

    function migrateDatabase(SymfonyStyle $io) {
        $scriptOutput = null;
        $returnValue = null;

        $io->section('Migrating the database');
        $io->text('Executing the PHP script:');
        $io->text('php bin/console doctrine:migrations:migrate -n --env='.$_ENV["APP_ENV"]);
        $io->newLine();

        exec('php bin/console doctrine:migrations:migrate -n --env='.$_ENV["APP_ENV"], $scriptOutput, $returnValue);

        $io->text('The PHP script returned:');
        $io->text($scriptOutput);
        $io->newLine();

        return $returnValue;
    }
}

```
<br>

Add to the environmental variables. This means pasting the following block into 3 files. Of course, the `.env.local` files will need actual unique values. Copy and paste these values from where they are defined for Doctrine ORM/DBAL.

+ `.env` exists in all environments
+ `.env.local` exists in the dev environment (i.e., local computer)
+ `.env.local` exists in the prod environment (i.e., GT Plesk Web Hosting)

```
###> database credentials for backup script ###
DB_USERNAME=something-admin
DB_PASSWORD=randomcharacters
DB_SCHEMA=something_db
###< database credentials for backup script ###
```
<br>

Use the database set-up command.
```
php bin/console tce3:database-setup Risk-Accepted
```
<br>

Because database migrations bring the risk of data loss and because this command will ultimately be run as part of `composer install`, an additional "double_check" argument is required.

Possible values for the "double_check" argument. (case sensitive)

+ `Risk-Accepted` = The command will run.
+ `bypass` = The command will not run, but Success will be returned. This is necessary for using the command in `composer install`.
+ anything else = The command will not run and will error out.
<br>
<br>

When the command is run successfully, a few things will happend.

+ There will be new sql file in `data/sql/backups` containing a dump of the existing data in the schema prior to any Doctrine commands being run.
+ Doctrine will create any databases that are needed by ORM/DBAL but do not yet exist in the environment wherein the `tce3:database-setup` command was run.
+ Doctrine will migrate the latest migration.
<br>
<br>

_NB: Due to the limitations of using GT Plesk Web Hosting, a new migration cannot be made in prod. Migrations must be made in dev and then migrated to prod._
<br>

Tell Composer to run `tce3:database-setup` when `composer install` is run. Edit the `composer.json` file.
```
"scripts": {
    "auto-scripts": {
        "cache:clear": "symfony-cmd",
        "assets:install %PUBLIC_DIR%": "symfony-cmd"
    },
    "post-install-cmd": [
        "@auto-scripts",
        "php bin/console tce3:database-setup bypass"
    ],
    "post-update-cmd": [
        "@auto-scripts"
    ]
},
```
<br>

This way, the database set-up command will run when `composer install` is run but not when `composer update` is run. Be aware of the "double_check" argument. It's wise to leave the value as `bypass` in source control and only manually change it to `Risk-Accepted` when a Doctrine migration is needed.
<br>
<br>

_Is it possible to run php console commands or symfony console commands in the command line in the prod environment?_ 

Nope. You would need greater permissions than what they allow you to have. That's the reason for creating a command and putting it in the `scripts` block of the `composer.json` file. Only when using Composer can such commands be executed.
<br>
<br>

Make sure data files are kept out of source control (i.e, Git). Edit the `.gitignore` file, appending the following:
```
# Exclude data for uploads and from backups
/data/
```
<br>

<a id="20"></a>
<a href="#top">Back to top</a>

### 20. Load data

Mini-glossary:<br>
+ _CSV = comma-separated values_<br>
<br>

Make a directory to hold csv files containing `User` data to upload.
```
mkdir -m777 -p -v data/csv/uploads/User
```
<br>

Make a csv file to get started with some sample `User` data.
```
touch data/csv/uploads/User/starter1.csv
```
<br>

Edit the file at `data/csv/uploads/User/starter1.csv` by adding the following.
```
"username","orgID","displayName","email","category",status,frozen,"roles"
"admin","100000000","Admin","admin@example.edu","faculty",1,1,"ROLE_ADMIN"
"burnhamm","100000001","Michael Burnham","michael.burnham@example.edu","staff",1,1,"ROLE_ADMIN"
"janewayk","100000002","Kathryn Janeway","kathryn.janeway@example.edu","faculty",1,1,"ROLE_FACULTY"
"uhuran","100000003","Nyota Uhura","nyota.uhura@example.edu","staff",1,1,"ROLE_STAFF"
"crusherb","100000004","Beverly Crusher","beverly.crusher@example.edu","student",1,1,"ROLE_STUDENT"
"daxj","100000005","Jadzia Dax","jadzia.dax@example.edu","applicant",1,1,"ROLE_GSAPP"
"satoh","100000006","Hoshi Sato","hoshi.sato@example.edu","applicant",1,1,"ROLE_UGAPP"
"hansena","100000007","Annika Hansen","annika.hansen@example.edu","member",1,1,"ROLE_USER"
"marinerb","100000008","Beckett Mariner","beckett.mariner@example.edu","faculty",1,1,"ROLE_FACULTY,ROLE_STAFF"
"ortegase","100000009","Erica Ortegas","eria.ortegas@example.edu","student",1,1,"ROLE_STUDENT,ROLE_GSAPP"
"kes","100000010","Kes","kes@example.edu","member",0,1,"ROLE_STAFF"
```

_NB: The format of any csv file being parsed for user data must conform to what's shown above or else the command simply won't work._
<br>
<br>

Create a `DataLoad` command.
```
php bin/console make:command data-load
```
<br>

Edit the file at `src/Command/DataLoadCommand.php` by adding the following.
```
<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'data-load',
    description: "This command can be added to the composer.json file so it can run Composer's install and/or update processes.",
    hidden: false,
    aliases: ['tce3:data-load','tce3:load-data']
)]
class DataLoadCommand extends Command
{
    private $loadableEntities = ['User'];

    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }
    
    protected function configure(): void
    {
        $this
            ->addArgument('targetEntity', InputArgument::REQUIRED, 'Indicate the entity type')
            ->addArgument('fileToLoad', InputArgument::REQUIRED, 'Indicate the file to load')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $targetEntity = $input->getArgument('targetEntity');
        $fileToLoad = $input->getArgument('fileToLoad');

        if ($targetEntity === 'User') {
            $io->title("Loading users");
            return $this->loadUsers($io, $targetEntity, $fileToLoad);
        }

        $io->warning('Invalid.');
        return Command::INVALID;
    }

    protected function loadUsers(SymfonyStyle $io, string $targetEntity, string $fileToLoad) {
        if ($this->runChecks($io, $targetEntity, $fileToLoad, 8, 2) === 0) {
            $this->parseUserFileAndLoadUsers($io, $fileToLoad);
        } else {
            $io->warning('Users from '.$fileToLoad.' have NOT been loaded.');
            return Command::FAILURE;
        }
        $io->success('Users from '.$fileToLoad.' have been loaded.');
        return Command::SUCCESS;
    }

    protected function parseUserFileAndLoadUsers(SymfonyStyle $io, string $fileToLoad) {
        $io->section("Parsing csv file and inserting users into database");
        $denominator = $this->getExpectedNumberOfNewRecords('User', $fileToLoad);
        $row = 0;
        if (($handle = fopen("data/csv/uploads/User/{$fileToLoad}", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($row > 0) {
                    $this->persistUserToUserTable($io, $data, $fileToLoad, (string)$denominator, (string)$row);
                }
                $row++;
            }
            fclose($handle);
        }
        $io->newLine();
    }

    protected function persistUserToUserTable(SymfonyStyle $io, array $row, string $fileToLoad, string $total, string $current) {
        $user = new User();

        $user->setUsername($row[0]);
        $user->setOrgID($row[1]);
        $user->setDisplayName($row[2]);
        $user->setEmail($row[3]);
        $user->setCategory($row[4]);
        $user->setStatus((int) $row[5]);
        $user->setFrozen((int) $row[6]);
        $user->setLoadedFrom($fileToLoad);

        $rolesToLoad = [User::ROLE_USER];
        if (!empty($row[7])) {
            $roleArr = explode(',', $row[7]);
            $rolesToLoad = $this->getRolesToLoad($roleArr);
        };
        $user->setRoles($rolesToLoad);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->text(
            sprintf("%04d/%04d\t%s\t%15s\t%s\t%30s\t%s", 
            $current, 
            $total, 
            $user->getId(), 
            $user->getUsername(), 
            $user->getOrgID(), 
            $user->getDisplayName(),
            implode(',',$user->getRoles())
        ));
    }

    protected function runChecks(SymfonyStyle $io, string $targetEntity, string $fileToLoad, int $exactNumOfCols, int $minNumOfRows): int {
        $io->section("Running checks");

        if ($this->checkTargetEntityIsLoadable($targetEntity)) {
            $io->success('Target entity is loadable.');
        } else {
            $io->warning('Cannot verify that target entity is loadable.');
            return Command::FAILURE;
        }

        if ($this->checkFileToLoadExists($targetEntity, $fileToLoad)) {
            $io->success('File to load exists.');
        } else {
            $io->warning('Cannot verify that file to load exists.');
            return Command::FAILURE;
        }

        if ($this->checkFileToLoadColumnLength($targetEntity, $fileToLoad, $exactNumOfCols)) {
            $io->success('File to load has valid column length.');
        } else {
            $io->warning('Cannot verify that file to load has valid column length.');
            return Command::FAILURE;
        }

        if ($this->checkFileToLoadRowLength($io, $targetEntity, $fileToLoad, $minNumOfRows)) {
            $io->success('File to load has valid row length.');
        } else {
            $io->warning('Cannot verify that file to load has valid row length.');
            return Command::FAILURE;
        }

        $io->newLine();
        return Command::SUCCESS;
    }

    protected function checkTargetEntityIsLoadable(string $targetEntity): bool {
        return in_array($targetEntity, $this->loadableEntities);
    }

    protected function checkFileToLoadExists(string $targetEntity, string $fileToLoad): bool {
        return file_exists("data/csv/uploads/{$targetEntity}/{$fileToLoad}");
    }

    protected function checkFileToLoadColumnLength(string $targetEntity, string $fileToLoad, int $exactNumOfCols): bool {
        if (($handle = fopen("data/csv/uploads/{$targetEntity}/{$fileToLoad}", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if (count($data) === $exactNumOfCols) {
                    fclose($handle);
                    return true;
                }
            }
            fclose($handle);
        }
        return false;
    }

    protected function checkFileToLoadRowLength(SymfonyStyle $io, string $targetEntity, string $fileToLoad, int $minNumOfRows): bool {
        $row = 0;
        if (($handle = fopen("data/csv/uploads/{$targetEntity}/{$fileToLoad}", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                for ($col=0; $col < count($data); $col++) {
                    // $io->text('Cell #'.$row.'-'.($col+1).': '.$data[$col]);
                }
                $row++;
            }
            // $io->text('Row length = '.$row);
            if ($row >= $minNumOfRows) {
                fclose($handle);
                return true;
            }
            fclose($handle);
        }
        return false;
    }

    protected function getExpectedNumberOfNewRecords(string $targetEntity, string $fileToLoad): int {
        $row = 0;
        if (($handle = fopen("data/csv/uploads/{$targetEntity}/{$fileToLoad}", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $row++;
            }
            fclose($handle);
        }
        --$row;
        return $row;
    }

    protected function getRolesToLoad(array $roleArr): array {
        $rolesToLoad = [];
        foreach ($roleArr as $role) {
            switch ($role) {
                case 'ROLE_ADMIN':
                    $rolesToLoad[] = User::ROLE_ADMIN;
                    break;
                case 'ROLE_FACULTY':
                    $rolesToLoad[] = User::ROLE_FACULTY;
                    break;
                case 'ROLE_STAFF':
                    $rolesToLoad[] = User::ROLE_STAFF;
                    break;
                case 'ROLE_STUDENT':
                    $rolesToLoad[] = User::ROLE_STUDENT;
                    break;
                case 'ROLE_GSAPP':
                    $rolesToLoad[] = User::ROLE_GSAPP;
                    break;
                case 'ROLE_UGAPP':
                    $rolesToLoad[] = User::ROLE_UGAPP;
                    break;
                default:
                    $rolesToLoad[] = User::ROLE_USER;
                    break;
            }
        }
        return $rolesToLoad;
    }
}

```
<br>

Run the `data-load` (aka `load-data`) command to parse the CSV file located at `csv/uploads/User/starter1.csv` and save the data as `User` data in the database.
```
php bin/console tce3:data-load User starter.csv
```
<br>

At this point, use a database client, IDE, or phpMyAdmin to see the data in the database. Inspect it carefully. Many kinds of errors can occur without the process indicating that they occurred.
<br>
<br>

<a id="21"></a>
<a href="#top">Back to top</a>

### 21. Unload data

Create a `DataUnload` command.
```
php bin/console make:command data-unload
```
<br>

Edit the file at `src/Command/DataUnloadCommand.php` by adding the following.
```
<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'data-unload',
    description: "This command can be added to the composer.json file so it can run Composer's install and/or update processes.",
    hidden: false,
    aliases: ['tce3:data-unload','tce3:unload-data']
)]
class DataUnloadCommand extends Command
{
    private $unloadableEntities = ['User'];

    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }
    
    protected function configure(): void
    {
        $this
            ->addArgument('targetEntity', InputArgument::REQUIRED, 'Indicate the entity type')
            ->addArgument('fileToUnload', InputArgument::REQUIRED, 'Indicate the file to unload')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $targetEntity = $input->getArgument('targetEntity');
        $fileToUnload = $input->getArgument('fileToUnload');

        if ($targetEntity === 'User') {
            $io->title("Unloading users");
            return $this->unloadUsers($io, $targetEntity, $fileToUnload);
        }

        $io->warning('Invalid.');
        return Command::INVALID;
    }

    protected function unloadUsers(SymfonyStyle $io, string $targetEntity, string $fileToUnload): int {
        if ($this->runChecks($io, $targetEntity, $fileToUnload, 8, 2) === 0) {
            $this->deleteUsersFromUserTable($io, $fileToUnload);
        } else {
            $io->warning('Users from '.$fileToUnload.' have NOT been unloaded.');
            return Command::FAILURE;
        }
        $io->success('Users from '.$fileToUnload.' have been unloaded.');
        return Command::SUCCESS;
    }

    protected function deleteUsersFromUserTable(SymfonyStyle $io, string $fileToUnload) {
        $io->section("Deleting users from database");
        $users = $this->entityManager->getRepository(User::class)->findBy(['loadedFrom'=>$fileToUnload]);
        $total = count($users);
        $i = 1;
        foreach ($users as $user) {
            $io->text(
                sprintf("%04d/%04d\t%s\t%15s\t%s\t%30s\t%s", 
                $i, 
                $total, 
                $user->getId(), 
                $user->getUsername(), 
                $user->getOrgID(), 
                $user->getDisplayName(),
                implode(',',$user->getRoles())
            ));

            $this->entityManager->remove($user);
            $this->entityManager->flush();

            $i++;
        }
        $io->newLine();
    }

    protected function runChecks(SymfonyStyle $io, string $targetEntity, string $fileToUnload): int {
        $io->section("Running checks");
        if ($this->checkTargetEntityIsUnloadable($targetEntity)) {
            $io->success('Target entity is unloadable.');
        } else {
            $io->warning('Cannot verify that target entity is unloadable.');
            return Command::FAILURE;
        }

        if ($this->checkFileToUnloadIsLoaded($targetEntity, $fileToUnload)) {
            $io->success('File to unload is loaded.');
        } else {
            $io->warning('Cannot verify that file to unload is loaded.');
            return Command::FAILURE;
        }
        $io->newLine();
        return Command::SUCCESS;
    }

    protected function checkTargetEntityIsUnloadable(string $targetEntity): bool {
        return in_array($targetEntity, $this->unloadableEntities);
    }

    protected function checkFileToUnloadIsLoaded(string $targetEntity, string $fileToUnload): bool {
        if ($targetEntity === 'User') {
            if ($this->entityManager->getRepository(User::class)->findBy(['loadedFrom'=>$fileToUnload], null, 1)) {
                return true;
            }
        }
        return false;
    }
}

```
<br>

Run the `data-unload` (aka `unload-data`) command to delete the data that previously came from the `data-load` command.
```
php bin/console tce3:data-unload User starter1.csv
```
<br>

Now, using a database client, IDE, or phpMyAdmin to see the data in the database, you should be able to see data appear after running `data-load` and disappear after running `data-unload`.
<br>
<br>

#### Load and unload data in the prod environment

To user the `data-load` and `data-unload` in the prod environment -- after making sure the latest Git commits are pulled in and the latest Doctrine migration is migrated -- you must:

+ use the Plesk GUI to create the necessary directory for the csv (e.g., `data/csv/uploads/User`)
+ use the Plesk GUI to create the csv file **OR** use the Plesk GUI to upload the already-created csv file (e.g., `starter1.csv`)
+ overwrite the prod environment's `composer.json` file **temporarily** as below
+ use the Plesk GUI to do an "Install" using Composer

```
"post-install-cmd": [
    "@auto-scripts",
    "php bin/console tce3:database-setup bypass",
    "php bin/console tce3:data-load User starter1.csv"
],
```
<br>

After loading or unloading data in this way, be sure to undue any changes to `composer.json`. Also, be aware that `composer.json` will be overwritten with the version stored in source control whenever the latest pull from Git occurs.
<br>
<br>

<a id="22"></a>
<a href="#top">Back to top</a>

### 22. Add dev authorization

Since the `http_basic` means of dev authentication cannot mimic prod authentication sufficiently -- it does not create a `User` object for the current user -- you must now create a custom `DevAuthenticator`. With this dev authenticator in place, it will be possible to use the `User` table of the database of dev authorization.

Create a new file at `src/Security/DevAuthenticator.php`
```
<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class DevAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function supports(Request $request): ?bool
    {
        return true;
    }

    public function authenticate(Request $request): Passport
    {
        $envDevUser = $this->entityManager->getRepository(User::class)->findOneBy(['username'=>$_ENV['DEV_USER']]);

        if (!$envDevUser) {
            throw new CustomUserMessageAuthenticationException('User not found in database');
        }

        if ($envDevUser->getStatus() !== 1) {
            throw new CustomUserMessageAuthenticationException('User is blocked');
        }

        $this->logger->debug('DevAuthenticator authenticating as '.$envDevUser->getUsername());
        return new SelfValidatingPassport(
            new UserBadge(
                $_ENV['DEV_USER'],
                static fn (): UserInterface => $envDevUser
            )
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // on success, let the request continue
        $this->logger->debug('DevAuthenticator authentication success');
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        ];
        $this->logger->debug('DevAuthenticator authentication failure');
        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}

```
<br>

Notice it uses an environmental variable: `$_ENV['DEV_USER`]. This variable must be defined in the `.env.local` file of the dev environment.
```
###> username of user when in dev ###
# Seriously, this is just for dev!! #
DEV_USER=admin
###< username of user when in dev ###
```
<br>

Now, rewrite the `when@dev` clause of the security configuration. Edit the file at `config/packages/security.yaml`.

_NB: When working with YAML files identation and spacing can be make-or-break!_

```
when@dev:
    security:
        providers:
            database_users:
                entity: { class: App\Entity\User, property: username }
        firewalls:
            dev:
                pattern: ^/(_(profiler|wdt)|css|images|js)/
                security: false
            homepage_pass_thru:
                security: false
                request_matcher: App\Security\HomepageMatcher
            never_for_production:
                lazy: true
                provider: database_users
                custom_authenticator: App\Security\DevAuthenticator
        access_control:
            - { path: ^/admin, roles: ROLE_ADMIN }
            - { path: ^/my, roles: ROLE_USER }
        role_hierarchy:
            ROLE_ADMIN: [ROLE_FACULTY,ROLE_STAFF,ROLE_STUDENT,ROLE_GSAPP,ROLE_UGAPP,ROLE_USER]
            ROLE_FACULTY: ROLE_USER
            ROLE_STAFF: ROLE_USER
            ROLE_STUDENT: ROLE_USER
            ROLE_GSAPP: ROLE_USER 
            ROLE_UGAPP: ROLE_USER

```
<br>

Notice the `homepage_pass_thru` firewall. It ensures no logging in is required for the homepage. Notice the custom `request_matcher` it has. This class must be created now.

Create the `HomepageMatcher` class. Make a new file at `src/Security/HompageMatcher.php`.
```
<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;

final class HomepageMatcher implements RequestMatcherInterface
{
    /**
     * Decides whether the rule(s) implemented by the strategy matches the supplied request.
     */
    public function matches(Request $request): bool {
        $routeName = $request->attributes->get('_route');
        if ($routeName === 'homepage') {
            return true;
        }
        return false;
    }
}

```
<br>

There might be superfluous stuff in the `/my` route. Edit the `/my` route in the `PageController` at `src/Controller/PageController.php`.
```
#[Route('/my', name: 'secure_page')]
public function secure(LoggerInterface $logger): Response
{
    return $this->render('page/index.html.twig', [
        'page_title' => 'Secure Page',
        'page_content' => 'This is a secure page.',
    ]);
}
```
<br>

Change the base Twig template to show the current user's details when possible. Edit the file at `templates/base.html.twig`.
```
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{% block site_title %}Welcome!{% endblock %}</title>
        <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🛠️</text></svg>">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
        {% block stylesheets %}
        {% endblock %}
    </head>
    <body>
        <div class="col-lg-8 mx-auto p-4 py-md-5">
            <header class="d-flex align-items-center pb-3 mb-5 border-bottom">
                {% block heading %}
                <h1 class="text-body-emphasis">Hello, world.</h1>
                {% endblock %}
            </header>
            <main>
                <div class="col-md-6">
                {% block content %}
                <p class="fs-5">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc ut aliquam tortor.</p>
                {% endblock %}
                </div>

                {% if (is_granted('ROLE_USER') or is_granted('ROLE_CAS_AUTHENTICATED'))%}
                    <div class="col-md-6 mt-5">
                    <p>UUID: {{ app.user.id }}</p>
                    <p>Org ID: {{ app.user.orgID }}</p>
                    <p>Display Name: {{ app.user.displayName }}</p>
                    <p>Email: {{ app.user.email }}</p>
                    <p>Category: {{ app.user.category }}</p>
                    <p>Status: {{ app.user.status }}</p>
                    <p>Frozen: {{ app.user.frozen }}</p>
                    <p>Loaded From: {{ app.user.loadedFrom }}</p>
                    <p>Created: {{ app.user.created | date('Y-m-d h:i:s')}}</p>
                    <p>Updated: {{ app.user.updated | date('Y-m-d h:i:s')}}</p>
                    <p>Roles:{% for role in app.user.roles %}({{ role }}) {% endfor %}</p>
                    </div>
                {% endif %}
            </main>
            <footer class="pt-5 my-5 text-body-secondary border-top">
                Made with 🍕 by barberod &middot; &copy; 2023
            </footer>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
        {% block javascripts %}
        {% endblock %}
    </body>
</html>
```
<br>

Bringing it all together, you can now clear the cache, start the dev server, and navigate among the available routes: `/`, `/my`, and `/admin`.
```
php bin/console cache:clear
```
```
symfony server:start
```
<br>

At this point, Symfony Profiler will be showing its value.

To test the app with various users and roles, change the value of `$_ENV['DEV_USER']` in the `.env.local` file.<br>
You can use any username from the set that was loaded from `starter1.csv`. These are just 4 examples.
```
###> username of user when in dev ###
# Seriously, this is just for dev!! #
DEV_USER=burnhamm
###< username of user when in dev ###
```
```
###> username of user when in dev ###
# Seriously, this is just for dev!! #
DEV_USER=uhuran
###< username of user when in dev ###
```
```
###> username of user when in dev ###
# Seriously, this is just for dev!! #
DEV_USER=hansena
###< username of user when in dev ###
```
```
###> username of user when in dev ###
# Seriously, this is just for dev!! #
DEV_USER=marinerb
###< username of user when in dev ###
```
<br>

<a id="23"></a>
<a href="#top">Back to top</a>

### 23. Add flash alerts

Symfony makes it easy to show little notifications to the user.

"You can store special messages, called "flash" messages, on the user's session. By design, flash messages are meant to be used exactly once: they vanish from the session automatically as soon as you retrieve them. This feature makes "flash" messages particularly great for storing user notifications." (Symfony docs)

Edit the `DevAuthenticator` at `src/Security/DevAuthenticator.php`.
```
...
use Symfony\Component\HttpFoundation\RequestStack;
...

...
use Symfony\Bundle\SecurityBundle\Security;
...

...
public function __construct(
    private readonly EntityManagerInterface $entityManager,
    private readonly LoggerInterface $logger,
    private readonly Security $security,
    private readonly RequestStack $requestStack
) {}
...

...
public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
{
    // on success, let the request continue
    $user = $this->security->getUser();
    $alertText = sprintf("Successfully logged in as %s (%s).", $user->getDisplayName(), $user->getUsername());
    $request->getSession()->getFlashBag()->add('success', $alertText);
    
    $this->logger->debug('DevAuthenticator authentication success');
    return null;
    }
...

...
protected function generateLoggedInFlashMessage(Request $request, User $user): void {
    $alertText = sprintf("Successfully logged in as %s (%s).", $user->getDisplayName(), $user->getUsername());
    $request->getSession()->getFlashBag()->add('success', $alertText);
}

protected function keepsExistingUsernameInSession(string $givenUsername): bool {
    if ($givenUsername === $this->getExistingUsernameInSession()) {
        return true;
    }
    return false;
}

protected function getExistingUsernameInSession(): ?string {
    if ($this->requestStack->getSession()->get('existingUsername')) {
        return $this->requestStack->getSession()->get('existingUsername');
    }
    return null;
}

protected function setUsernameInSession(string $givenUsername): void {
    $this->requestStack->getSession()->set('existingUsername', $givenUsername);
}
...
```
<br>

Then make sure the Twig template will show the flash message(s).

Edit the Twig template at `templates/base.html.twig`. Paste the following just above the `header` element that contains the `h1` element. 

This Twig/html snippet assumes the site is using Bootstrap v.5.3 or thereabouts.
```
{% for label, messages in app.flashes(['danger', 'info', 'success', 'warning']) %}
    {% for message in messages %}
        <div class="alert alert-{{ label }} alert-dismissible fade show" role="alert">
            {{ message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    {% endfor %}
{% endfor %}
```
<br>

Clear the cache. Run the dev server. Load the `/my` page. See an alert containing the flash message.
<br>
<br>

<a id="24"></a>
<a href="#top">Back to top</a>

### 24. Add prod authorization

Mini-glossary:<br>
+ _GTED = Georgia Tech Enterprise Directory_<br>
+ _LDAP = Lightweight Directory Access Protocol_<br>
<br>

In the dev environment, the php module to support LDAP might not be installed. In that case, run this:
```
sudo apt-get install php-ldap
```
<br>

Install the Symfony LDAP package. 
```
composer require symfony/ldap
```
<br>

Make a service to look up people in GTED by username. Create a directory at `/Service` and a file at `/Service/LookupService.php`.

To get started, if you need it, the service has a function for getting dev data instead of real data. That's the difference between `getDevUserData()` and `getUserData()`.
```
<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Ldap\Entry;
use Symfony\Component\Ldap\Ldap;

class LookupService
{
    private EntityManagerInterface $entityManager;
    private LoggerInterface $logger;

    public function __construct(
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    ) {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    public function processUser(string $givenUsername): User
    {
        $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['username'=>$givenUsername]);

        if (!$existingUser) {
            $this->saveNewUser($givenUsername);
        } else {
            $this->updateExistingUserIfNeeded($existingUser);
        }
        
        return $this->entityManager->getRepository(User::class)->findOneBy(['username'=>$givenUsername]);
    }

    protected function saveNewUser(string $givenUsername): User {
        $user = new User();
        // $userData = $this->getDevUserData($givenUsername);
        $userData = $this->getUserData($givenUsername);

        $this->logger->debug(print_r($userData,true));

        $user->setUsername($userData['username']);
        $user->setOrgID($userData['org_id']);
        $user->setDisplayName($userData['display_name']);
        $user->setEmail($userData['email']);
        $user->setCategory($userData['category']);
        $user->setStatus($userData['status']);
        $user->setFrozen($userData['frozen']);
        $user->setLoadedFrom($userData['loaded_from']);
        $user->setRoles($userData['roles']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    protected function updateExistingUserIfNeeded(User $existingUser): User {
        if ($existingUser->getFrozen() === 1) {
            return $existingUser;
        }

        // $userData = $this->getDevUserData($existingUser->getUsername());
        $userData = $this->getUserData($existingUser->getUsername());
        if (
            $existingUser->getOrgID() !== $userData["org_id"] ||
            $existingUser->getDisplayName() !== $userData["display_name"] ||
            $existingUser->getEmail() !== $userData["email"] ||
            $existingUser->getCategory() !== $userData["category"] ||
            $existingUser->getStatus() !== $userData["status"] ||
            $existingUser->getRoles() !== $userData["roles"]
        ) {
            $existingUser->setOrgID($userData["org_id"]);
            $existingUser->setDisplayName($userData["display_name"]);
            $existingUser->setEmail($userData["email"]);
            $existingUser->setCategory($userData["category"]);
            $existingUser->setStatus($userData["status"]);
            $existingUser->setRoles($userData["roles"]);
            $existingUser->setLoadedFrom($userData["loaded_from"]);
            $this->entityManager->flush();

            return $this->entityManager->getRepository(User::class)->findOneBy(['username'=>$existingUser->getUsername()]);
        }

        return $existingUser;
    }

    public function getUserData(string $givenUsername): array
    {
        $userData = array();

        $ldap = Ldap::create('ext_ldap', ['connection_string' => $_ENV["LDAP_HOST"].':'.(int)$_ENV["LDAP_PORT"]]);
        $ldap->bind($_ENV["LDAP_DN"], $_ENV["LDAP_PW"]);
        $query = $ldap->query('ou=accounts,ou=gtaccounts,ou=departments,dc=gted,dc=gatech,dc=edu', "(&(uid={$givenUsername}))");
        $result = $query->execute();
        $userData = $this->crosswalkLdapDataToUserData($givenUsername, $result[0]);

        return $userData;
    }

    private function crosswalkLdapDataToUserData(string $givenUsername, Entry $entry): array {
        $userData = array();
        $userData['username'] = $givenUsername;
        $userData['org_id'] = $this->getIndexZero($entry->getAttribute('gtGTID'));
        $userData['display_name'] = $this->getIndexZero($entry->getAttribute('displayName'));
        $userData['email'] = $this->getIndexZero($entry->getAttribute('gtPrimaryEmailAddress'));
        $userData['category'] = $this->getIndexZero($entry->getAttribute('eduPersonPrimaryAffiliation'));
        $userData['status'] = 1;
        $userData['frozen'] = 0;
        $userData['loaded_from'] = 'ldap-'.$this->generateRandomString(5);
        $userData['roles'] = $this->determineRoles($entry);
        return $userData;
    }

    private function determineRoles(Entry $entry): array {
        $roles = array();
        if ($this->isUndergraduateApplicant($entry)) { $roles[] = User::ROLE_UGAPP; };
        if ($this->isGraduateApplicant($entry)) { $roles[] = User::ROLE_UGAPP; };
        if ($this->isStudent($entry)) { $roles[] = User::ROLE_STUDENT; };
        if ($this->isStaff($entry)) { $roles[] = User::ROLE_STAFF; };
        if ($this->isFaculty($entry)) { $roles[] = User::ROLE_FACULTY; };
        if ($this->isAdmin($entry)) { $roles[] = User::ROLE_ADMIN; };
        return $roles;
    }

    private function isUndergraduateApplicant(Entry $entry): bool {
        if (in_array("undergrad-applicant@gt", $entry->getAttribute('eduPersonScopedAffiliation'))) {
            return true;
        }
        return false;
    }

    private function isGraduateApplicant(Entry $entry): bool {
        if (in_array("grad-applicant@gt", $entry->getAttribute('eduPersonScopedAffiliation'))) {
            return true;
        }
        return false;
    }

    private function isStudent(Entry $entry): bool {
        if (
            (in_array("student@gt", $entry->getAttribute('eduPersonScopedAffiliation'))) ||
            (in_array("former-credit-student@gt", $entry->getAttribute('eduPersonScopedAffiliation'))) ||
            (in_array("credit-applicant-confirmed@gt", $entry->getAttribute('eduPersonScopedAffiliation')))
        ) {
            return true;
        }
        return false;
    }

    private function isStaff(Entry $entry): bool {
        if (
            (in_array("staff@gt", $entry->getAttribute('eduPersonScopedAffiliation'))) ||
            (in_array("faculty@gt", $entry->getAttribute('eduPersonScopedAffiliation'))) ||
            (in_array("affiliate@gt", $entry->getAttribute('eduPersonScopedAffiliation'))) ||
            (in_array("retiree@gt", $entry->getAttribute('eduPersonScopedAffiliation'))) ||
            (in_array("full-time-employee@gt", $entry->getAttribute('eduPersonScopedAffiliation')))
        ) {
            return true;
        }
        return false;
    }

    private function isFaculty(Entry $entry): bool {
        if (
            (in_array("faculty@gt", $entry->getAttribute('eduPersonScopedAffiliation'))) ||
            (in_array("affiliate@gt", $entry->getAttribute('eduPersonScopedAffiliation')))
        ) {
            return true;
        }
        return false;
    }

    private function isAdmin(Entry $entry): bool {
        if (
            (in_array("staff@enrollment", $entry->getAttribute('eduPersonScopedAffiliation'))) ||
            (in_array("staff@psdept 653:oit-eis:oit-enterprise information sys", $entry->getAttribute('eduPersonScopedAffiliation')))
        ) {
            return true;
        }
        return false;
    }

    public function getDevUserData(string $givenUsername): array
    {
        $userData = array();
        $userData['username'] = $givenUsername;
        $userData['org_id'] = $this->generateRandomID();
        $userData['display_name'] = $this->generateRandomString(5).' '.$this->generateRandomString(8);
        $userData['email'] = $this->generateRandomString(6).'@fake.gatech.edu';
        $userData['category'] = 'dev-user';
        $userData['status'] = 1;
        $userData['frozen'] = 0;
        $userData['loaded_from'] = 'lookup';
        $userData['roles'] = [User::ROLE_USER];
        return $userData;
    }

    private function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    private function generateRandomID() {
        $characters = '123456789';
        $randomString = '';
        for ($i = 0; $i < 9; $i++) {
            $randomString .= $characters[random_int(0, 8)];
        }
        return $randomString;
    }

    private function getIndexZero(array $someArr): string {
        if (isset($someArr[0])) {
            return $someArr[0];
        }
        return '';
    }
}


```
<br>

Edit the security configuration in the file at `config/packages/security.yaml`.
```
when@prod:    
    security:
        providers:
            custom_user_provider:
                entity:
                    class: App\Security\UserProvider
                    property: username
            database_users:
                entity: { class: App\Entity\User, property: username }
            chained_providers:
                chain:
                    providers: ['custom_user_provider', 'database_users']
        firewalls:
            dev:
                pattern: ^/(_(profiler|wdt)|css|images|js)/
                security: false
            homepage_pass_thru:
                security: false
                request_matcher: App\Security\HomepageMatcher
            secured:
                pattern: ^/
                provider: chained_providers
                custom_authenticator: EcPhp\CasBundle\Security\CasAuthenticator
                form_login:
                    check_path: cas_bundle_login
                    login_path: cas_bundle_login
                entry_point: EcPhp\CasBundle\Security\CasAuthenticator
        access_control:
            - { path: ^/my, roles: ROLE_CAS_AUTHENTICATED }
            - { path: ^/admin, roles: ROLE_ADMIN }
        role_hierarchy:
            ROLE_ADMIN: [ROLE_FACULTY,ROLE_STAFF,ROLE_STUDENT,ROLE_GSAPP,ROLE_UGAPP,ROLE_USER]
            ROLE_FACULTY: ROLE_USER
            ROLE_STAFF: ROLE_USER
            ROLE_STUDENT: ROLE_USER
            ROLE_GSAPP: ROLE_USER 
            ROLE_UGAPP: ROLE_USER
            ROLE_USER: ROLE_CAS_AUTHENTICATED

```
<br>

Create environmental variables to handle LDAP credentials. In your `.env` file, paste the dummy values below. In the `.env.local` file of the prod env, use real values.
```
###> ldap secrets ###
LDAP_HOST="ldaps://r.gted.gatech.edu"
LDAP_PORT=636
LDAP_DN="uid=USERNAME,ou=local accounts,dc=gted,dc=gatech,dc=edu"
LDAP_PW="PASSWORD"
###< ldap secrets ###
```
<br>

Edit `UserProvider` like so.
```
<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ecphp
 */

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use App\Service\LookupService;
use EcPhp\CasBundle\Security\Core\User\CasUser;
use EcPhp\CasBundle\Security\Core\User\CasUserInterface;
use EcPhp\CasBundle\Security\Core\User\CasUserProviderInterface;
use EcPhp\CasLib\Introspection\Contract\IntrospectorInterface;
use EcPhp\CasLib\Introspection\Contract\ServiceValidate;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;

use function get_class;

final class UserProvider implements CasUserProviderInterface
{
    private IntrospectorInterface $introspector;
    private LoggerInterface $logger;
    private LookupService $lookup;
    private RequestStack $requestStack;

    public function __construct(
        IntrospectorInterface $introspector,
        LoggerInterface $logger,
        LookupService $lookup,
        RequestStack $requestStack
    ) {
        $this->introspector = $introspector;
        $this->logger = $logger;
        $this->lookup = $lookup;
        $this->requestStack = $requestStack;
    }

    public function loadUserByIdentifier($identifier): UserInterface
    {
        throw new UnsupportedUserException('Unsupported operation.');
    }

    public function loadUserByResponse(ResponseInterface $response): CasUserInterface
    {
        try {
            $introspect = $this->introspector->detect($response);
        } catch (InvalidArgumentException $exception) {
            throw new AuthenticationException($exception->getMessage());
        }

        if ($introspect instanceof ServiceValidate) {
            return new CasUser($introspect->getCredentials());
        }

        throw new AuthenticationException('Unable to load user from response.');
    }

    public function loadUserByUsername(string $username): UserInterface
    {
        throw new UnsupportedUserException(sprintf('Username "%s" does not exist.', $username));
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof CasUserInterface) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }
        $this->logger->debug('****REFRESH USER**** = '.$user->getUserIdentifier());

        $processedUser = $this->lookup->processUser($user->getUserIdentifier());
        if ($processedUser->getStatus() !== 1) {
            throw new UnsupportedUserException(sprintf('User "%s" is blocked.', $user->getUserIdentifier()));
        }

        if (!($this->keepsExistingUsernameInSession($processedUser->getUsername()))) {
            $this->setUsernameInSession($processedUser->getUsername());
            $this->generateLoggedInFlashMessage($processedUser);
        }

        return $processedUser;
    }

    public function supportsClass(string $class): bool
    {
        return (CasUser::class === $class || User::class );
    }

    protected function generateLoggedInFlashMessage(User $user): void {
        $alertText = sprintf("Successfully logged in as %s (%s).", $user->getDisplayName(), $user->getUsername());
        $this->requestStack->getSession()->getFlashBag()->add('success', $alertText);
    }

    protected function keepsExistingUsernameInSession(string $givenUsername): bool {
        if ($givenUsername === $this->getExistingUsernameInSession()) {
            return true;
        }
        return false;
    }

    protected function getExistingUsernameInSession(): ?string {
        if ($this->requestStack->getSession()->get('existingUsername')) {
            return $this->requestStack->getSession()->get('existingUsername');
        }
        return null;
    }

    protected function setUsernameInSession(string $givenUsername): void {
        $this->requestStack->getSession()->set('existingUsername', $givenUsername);
    }

}

```
<br>

You'll need to run `composer install` on the dev env and prod env to ensure the Ldap packages are there after requiring them in the Composer config. Be sure you added the LDAP environmental variables to the prod env, too.

If you view the User table in the database after you log in, you'll see your account captured there.
<br>
<br>

<a id="25"></a>
<a href="#top">Back to top</a>

### 25. Add custom error page templates
https://www.digitalocean.com/community/tutorials/how-to-troubleshoot-common-http-error-codes

Lorem ipsum...
<br>
<br>

<a id="26"></a>
<a href="#top">Back to top</a>

### 26. Add unit testing with PHPUnit

Lorem ipsum...
<br>
<br>

<a id="27"></a>
<a href="#top">Back to top</a>

### 27. Add end-to-end (e2e) testing with Codeception

Lorem ipsum...
<br>
<br>

<a id="28"></a>
<a href="#top">Back to top</a>

### 28. Copy in front-end project

Lorem ipsum...
<br>
<br>

---
