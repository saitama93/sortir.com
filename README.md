# Installation

Une fois le projet cloné installer les déoendances nécessaires avec:
    
    composer install

Ensuite changer le .env avec les bon paramètres

Mettre à jour le schéma avec 

    php ./bin/console d:s:u -f

## HTACCESS

Si une erreur s'afficher lorsqu'on essaye d'atteindre la route /api rajouter un .htaccess avec la commande 

    composer require symfony/apache-pack