# Ayons le Cœur sur la Main — Laravel

Site public + administration, en français, responsive. Laravel 13 / PHP 8.3+ / MySQL ou MariaDB compatible Laravel. Blade, CSS et JavaScript natifs. Aucun Node, Vite, Redis, worker ni cron nécessaire pour utiliser le CMS. Les polices Google sont facultatives : les polices de repli restent utilisables sans accès externe.

Cette archive contient aussi `vendor/` et `composer.lock` avec les dépendances de production déjà installées. Pour la première mise en ligne, la commande `composer install --no-dev --optimize-autoloader` peut être omise si vous téléversez ce dossier `vendor/` complet. PHP et la base restent à configurer.

## Inclus

- Accueil reprenant le design du site précédent et présentant les dernières actualités et albums.
- Actualités paginées, pages d’articles, couverture, résumé et contenu Markdown avec HTML brut désactivé.
- Brouillons privés et publication à une date future (filtrage à chaque requête, sans tâche planifiée).
- Albums publics ou privés, ajout multiple de photos, légendes, ordre d’affichage et visionneuse.
- `/admin` protégé : tableau de bord, actualités, galerie, coordonnées, accueil, moyens de don, mot de passe.
- Lien WhatsApp et coordonnées de paiement ; aucun encaissement automatique ni suivi bancaire.
- Pas d’inscription publique. Compte administrateur créé en ligne de commande, sans identifiant ou mot de passe prédéfini.
- Migration et données initiales issues de l’ancien site (action du 14 septembre 2025 + photos). Pas de témoignages ou statistiques ajoutés.

Ce projet est un CMS éditorial. Il ne contient pas encore de CRM de gestion des membres, donateurs ou partenaires.

## Lancement local

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Pour tester sans serveur MySQL, créer `database/database.sqlite` puis modifier `.env` :

```dotenv
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000
DB_CONNECTION=sqlite
DB_DATABASE=/chemin/absolu/association-laravel/database/database.sqlite
SESSION_SECURE_COOKIE=false
```

Sous Windows, un chemin SQLite de type `D:/Travail/association-laravel/database/database.sqlite` fonctionne. Avec MySQL, conserver `DB_CONNECTION=mysql` et renseigner la base.

```bash
php artisan migrate --seed
php artisan storage:link
php artisan association:admin
php artisan serve
```

Site : `http://127.0.0.1:8000` ; administration : `/admin`. La commande `association:admin` demande le nom, l’e-mail et un mot de passe masqué. Elle peut réinitialiser un compte existant avec confirmation.

## Hébergement mutualisé (par exemple celui de Lepoto)

1. Ajouter un domaine ou sous-domaine **distinct de Lepoto** dans le panneau d’hébergement. Ne pas remplacer les fichiers, la base ni le `.env` de Lepoto.
2. Activer PHP **8.3 ou plus** et les extensions Laravel : Ctype, cURL, DOM/XML, Fileinfo, Mbstring, OpenSSL, PDO MySQL, Session, Tokenizer, ainsi que les extensions PHP usuelles. Vérifier aussi la version PHP de la ligne de commande.
3. Créer une **base MySQL dédiée** et un utilisateur avec accès à cette base.
4. Déposer le projet dans un dossier privé. La racine web doit pointer **exclusivement vers `public/`**, jamais vers la racine Laravel.
5. Copier `.env.example` vers `.env`, renseigner `APP_URL` avec l’URL HTTPS finale et les accès MySQL. Conserver `APP_ENV=production`, `APP_DEBUG=false`, `SESSION_SECURE_COOKIE=true`.
6. Depuis SSH ou le terminal fourni par l’hébergeur, exécuter :

```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --seed --force
php artisan storage:link
php artisan association:admin
php artisan optimize
```

7. Rendre `storage/` et `bootstrap/cache/` accessibles en écriture par PHP (adapter propriétaire/groupe ; ne pas utiliser `777`). Ouvrir le site puis `/admin`.
8. Tester un article en brouillon, sa publication, un album et une photo. Vérifier le téléchargement des images sous `/storage/`.

### Si le domaine impose `public_html/`

Laisser les dossiers privés (`app`, `vendor`, `.env`, `storage`…) **hors de `public_html`**. Copier uniquement le **contenu** de `public/`, y compris `.htaccess`, vers le `public_html` du domaine de l’association. Adapter les trois références `__DIR__.'/../...'` dans `public_html/index.php` pour viser le vrai dossier privé. Ne pas exposer la racine Laravel pour contourner ce réglage.

Dans ce montage, définir également le chemin public réel dans `bootstrap/app.php` : affecter le résultat de `Application::configure(...)->...->create()` à `$app`, puis ajouter `$app->usePublicPath('/chemin/absolu/vers/public_html'); return $app;`. Faire cette adaptation **avant** `migrate --seed` et `storage:link`, car les images initiales et le lien dépendent de `public_path()`.

Si les liens symboliques sont interdits, utiliser un hébergement qui les autorise ou configurer un disque public directement dans un dossier média de la racine web avec une URL correspondante. Ne pas laisser des images privées exposées par une route de téléchargement générique.

### Sans Composer sur le serveur

Sur un ordinateur disposant de PHP 8.3+ et Composer, exécuter `composer install --no-dev --optimize-autoloader`, puis téléverser aussi `vendor/` et `composer.lock`. Les dépendances ne sont pas incluses si l’archive livrée ne contient pas `vendor/`. La création du compte et les migrations nécessitent SSH/terminal ou une intervention de l’hébergeur : aucune page web d’installation privilégiée n’est fournie.

## Photos et limites PHP

Le CMS accepte jusqu’à 20 fichiers par envoi, 5 Mo chacun, uniquement JPG/PNG/WebP (les fichiers sont validés côté serveur). Ajuster `upload_max_filesize`, `post_max_size` et `max_file_uploads` dans le panneau PHP. `post_max_size` limite **l’ensemble** du formulaire ; choisir une limite adaptée à la mémoire et au forfait, ou téléverser par petits lots. Les images ne sont pas redimensionnées automatiquement ; compresser les photos très lourdes avant envoi.

## Tests

```bash
composer install
php artisan test
```

Les tests utilisent SQLite en mémoire, un stockage d’images simulé quand applicable, et couvrent notamment l’accès administrateur, les brouillons, les publications programmées, la validation des images, les albums privés, le Markdown, les paramètres, les écrans et les données initiales.

## Sauvegardes et maintenance

Sauvegarder ensemble la base, `storage/app/public` et le `.env` (en lieu sûr). Pour une mise à jour : sauvegarde, nouveau code, `composer install --no-dev --optimize-autoloader`, `php artisan migrate --force`, `php artisan optimize`. Ne pas remplacer la clé `APP_KEY` d’un site déjà installé. Le seeder initial ne doit pas être utilisé comme outil de restauration d’un contenu volontairement supprimé : il recrée l’actualité/l’album initial s’ils sont absents.

## Utilisation quotidienne

- **Actualités → Nouvelle actualité** : rédiger, ajouter une couverture, choisir Brouillon ou Publié, puis enregistrer.
- **Galerie photos → Nouvel album** : nom, description, photos ; cocher la visibilité et enregistrer. Ajouter les légendes et définir l’ordre sur chaque photo.
- **Informations du site** : modifier l’accueil, les coordonnées et les moyens de don.
- Cliquer **Voir le site public** pour vérifier le rendu.

Les modifications sont immédiatement partagées via la base et les fichiers sur le serveur. Aucun export ZIP ni redéploiement n’est nécessaire après une publication.
