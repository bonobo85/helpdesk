# Helpdesk Lapinski

Ce dépôt contient une application PHP de helpdesk simple pour XAMPP.

## Préparation avant push GitHub

1. Ne pas committer `config/config.php` : il contient la configuration locale de la base de données.
2. Copier `config/config.example.php` vers `config/config.php` et renseigner vos identifiants locaux.
3. Initialiser Git puis pousser vers GitHub :
   ```bash
   git init
   git add .
   git commit -m "Initial commit"
   git branch -M main
   git remote add origin <votre-url-github>
   git push -u origin main
   ```

## Structure du projet

- `index.php` : page d’accueil
- `inscription.php` : formulaire d’inscription
- `login.php` : formulaire de connexion
- `logout.php` : déconnexion
- `ticket.php` : gestion des tickets
- `config/` : configuration de la base de données
- `include/` : fichiers inclus (`auth.php`, `navbar.php`)
- `css/` : styles
- `js/` : scripts JavaScript

## Configuration locale

Copiez le fichier d’exemple :

```bash
cp config/config.example.php config/config.php
```

Puis modifiez `config/config.php` avec vos paramètres.
