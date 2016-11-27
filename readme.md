# BlogLite

BlogLite est un mini blog fonctionnant avec CodeIgniter 3 & SQLite 3. Pas besoin d'un serveur SQL, les données sont stockées dans un fichier :)  
La rédaction des articles s'effectue en Markdown avec la possibilité de taguer un article avec un ou plusieurs tags.  
Coté back, il existe 2 types de comptes utilisateurs :

- "administrateur"
- "modérateur"

Remarque : si SQLite ne fonctionne pas avec PHP, vérifiez que le module [`php_pdo_sqlite`](http://php.net/manual/fr/ref.pdo-sqlite.php) est bien activé.


## Serveur de dev avec Gulp

Gulp nécessite NodeJS, NPM et PHP installés sur votre machine (`node -v && npm -v && php -v`). 

1. Placez-vous dans le repertoire ce cette application (au même niveau que le fichier "Gulpfile.js") ;
2. Installez les dépendances présentes dans le fichier "package.json" avec `npm install` ;
3. Lancez le serveur avec `gulp`.


## Ingrédients

- CodeIgniter 3, framework PHP 5 MVC ;
- SQLite 3, base de données ;
- Foundation 6, framework CSS (utilisé coté front) : [http://foundation.zurb.com/sites/docs](http://foundation.zurb.com/sites/docs) ;
- Bootstrap 3, framework CSS (utilisé coté back) : [http://getboostrap.com](http://getboostrap.com) ;
- "ci-markdown", parseur Markdown : [https://github.com/jonlabelle/ci-markdown](https://github.com/jonlabelle/ci-markdown) ;
- "simple MDE", editeur Markdown : [https://simplemde.com](https://simplemde.com).


## API

Par défaut, l'API n'est pas publique (il faut être connecté au back office). Elle renvoie les données en Json.

### Articles

| Verb | URL | Description |
| ---- | --- | ----------- |
| GET | /admin/api/articles | Tous les articles |
| GET | /admin/api/articles/1 | Un article |


## Fonctionnalités présentes

### Front

- Lister les articles ;
- Lister par tags ;
- Afficher un article ;
- Formulaire de contact ;
- Feed RSS ;
- Moteur de recherche.

### Back office
- Ajouter / modifier / supprimer un article ;
- Ajouter / modifier / supprimer une page ;
- Ajouter / modifier / supprimer un utilisateur ;
- Configurer le blog pour :
    - titre du site ;
    - email de contact ;
    - réglage de la pagination (nombre d'articles par page) ;
    - description du site ;
    - slug reservés ;
- L'utilisateur peut éditer son profile et réinitialiser son mot de passe ;

## Todolist

> Un site n'est jamais fini...

- Traduire les messages d'erreurs en français ;
- Message de confirmation de suppression (JS) ;
- Ajouter une entité pour la date de publication ;
- Empêcher le modérateur de supprimer les autres articles ;
- S'authentifier avec login OU email.
