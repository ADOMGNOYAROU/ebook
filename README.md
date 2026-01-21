
# Plateforme Ebooks (Laravel)

Ce projet est une plateforme d'e-books (catalogue public + espace utilisateur + espace admin) développée avec Laravel.

## Sommaire

- Installation rapide (Windows / XAMPP)
- Configuration `.env`
- Lancer le projet
- Utilisation (visiteur / utilisateur / admin)
- Ajouter un e-book côté admin (gratuit / payant)
- Où sont stockés les fichiers (PDF, couvertures)
- Dépannage

## Installation rapide (Windows / XAMPP)

Prérequis :

- PHP (via XAMPP)
- Composer
- MySQL (via XAMPP)
- Node.js + npm

Depuis le dossier du projet :

1. Installer les dépendances PHP

   `composer install`

2. Installer les dépendances front

   `npm install`

3. Créer le fichier `.env`

   Copier `.env.example` en `.env`

4. Générer la clé Laravel

   `php artisan key:generate`

5. Migrer la base de données

   `php artisan migrate`

6. Créer le lien de stockage public (pour afficher les couvertures)

   `php artisan storage:link`

## Configuration `.env`

Dans `.env`, configure au minimum :

- `APP_URL` (ex: `http://127.0.0.1:8000`)
- `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`

Si tu utilises les paiements (Stripe/Cashier), configure aussi les clés Stripe (selon ta config `cashier`).

## Lancer le projet

1. Lancer le backend

   `php artisan serve`

2. Lancer le front (Vite)

   `npm run dev`

URLs utiles :

- Accueil : `http://127.0.0.1:8000/`
- Catalogue : `http://127.0.0.1:8000/ebooks`
- Dashboard utilisateur : `http://127.0.0.1:8000/dashboard`
- Admin : `http://127.0.0.1:8000/admin`

## Utilisation (débutant)

### Visiteur (non connecté)

- Voir la liste des ebooks
- Ouvrir la page détail d’un ebook

### Utilisateur (connecté)

- Télécharger un ebook gratuit
- Acheter un ebook payant (si la partie paiement est configurée)
- Retrouver l'historique des téléchargements

### Admin

L’espace admin est sous `/admin` et utilise un middleware `admin`.

Pour qu’un compte soit admin, il faut que sa colonne `role` (table `users`) soit réglée sur `admin`.

## Ajouter un e-book côté admin (gratuit / payant)

Aller dans l’admin, puis :

1. Ouvrir le formulaire "Ajouter un e-book"
2. Remplir :

- `Titre`
- `Auteur`
- `Description`
- `Catégorie`
- `Langue`
- `Fichier PDF`
- `Couverture`

3. **Choisir le type** (obligatoire) :

- `Gratuit` -> `is_free = 1`
- `Payant` -> `is_free = 0`

Note : si ton système de paiement est activé, l’ebook payant doit aussi avoir un prix défini (selon les champs disponibles dans ta base / ton formulaire).

## Où sont stockés les fichiers

### Couvertures

Les couvertures sont stockées sur le disque `public` :

- Dossier : `storage/app/public/covers`
- Accès web : via `/storage/...` (après `php artisan storage:link`)

### PDFs (e-books)

Les PDFs sont stockés sur le disque `local` configuré sur un dossier privé :

- Dossier : `storage/app/private/ebooks`

Le chemin est enregistré en base dans `ebooks.file_path` (ex: `ebooks/mon-livre.pdf`).

## Dépannage

### Les couvertures ne s'affichent pas

- Vérifie que le lien existe : `public/storage`
- Relance : `php artisan storage:link`

### Upload PDF/couverture échoue

- Vérifie les limites PHP (`upload_max_filesize`, `post_max_size`)
- Vérifie les permissions d’écriture sur `storage/` et `bootstrap/cache/`

### Le bouton “Télécharger” ne fait rien / le fichier est introuvable

- Vérifie que `ebooks.file_path` correspond à un fichier présent dans `storage/app/private/ebooks`
- Vérifie que l'ebook a bien un fichier PDF uploadé

