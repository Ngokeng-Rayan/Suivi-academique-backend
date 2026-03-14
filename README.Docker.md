# Guide de Dockerisation - Suivi Académique Backend

✅ **Application dockerisée avec succès!**

## 📋 Prérequis

- Docker Desktop installé
- Docker Compose installé

## ⚠️ Notes importantes

- Les migrations ont été corrigées pour résoudre les problèmes de types de clés étrangères
- La migration `add_support_cours_to_ec` a été renommée pour s'exécuter dans le bon ordre
- Les problèmes de permissions sur Windows sont normaux et n'affectent pas le fonctionnement

## 🚀 Démarrage rapide

### Option 1: Script automatique (Linux/Mac)

```bash
chmod +x docker-setup.sh
./docker-setup.sh
```

### Option 2: Commandes manuelles (Windows/Linux/Mac)

1. **Copier le fichier d'environnement**
```bash
cp .env.example .env
```

2. **Construire les images Docker**
```bash
docker-compose build
```

3. **Démarrer les conteneurs**
```bash
docker-compose up -d
```

4. **Installer les dépendances**
```bash
docker-compose exec app composer install
```

5. **Générer la clé d'application**
```bash
docker-compose exec app php artisan key:generate
```

6. **Créer le lien de stockage**
```bash
docker-compose exec app php artisan storage:link
```

7. **Exécuter les migrations**
```bash
docker-compose exec app php artisan migrate
```

8. **Configurer les permissions**
```bash
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

## 🌐 Accès à l'application

- **Application Laravel**: http://localhost:8000
- **Base de données MySQL**: localhost:3307
  - Database: `suivi_academique_backend`
  - Username: `root`
  - Password: `root`

## 📝 Commandes utiles

### Gestion des conteneurs

```bash
# Démarrer les conteneurs
docker-compose up -d

# Arrêter les conteneurs
docker-compose down

# Voir les logs
docker-compose logs -f

# Voir les logs d'un service spécifique
docker-compose logs -f app
docker-compose logs -f nginx
docker-compose logs -f db

# Redémarrer un service
docker-compose restart app
```

### Commandes Laravel

```bash
# Exécuter des commandes artisan
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear

# Accéder au shell du conteneur
docker-compose exec app bash

# Exécuter Composer
docker-compose exec app composer install
docker-compose exec app composer update
```

### Base de données

```bash
# Accéder à MySQL
docker-compose exec db mysql -u root -p

# Exporter la base de données
docker-compose exec db mysqldump -u root -proot suivi_academique_backend > backup.sql

# Importer une base de données
docker-compose exec -T db mysql -u root -proot suivi_academique_backend < backup.sql
```

## 🔧 Configuration

### Modifier les ports

Éditez `docker-compose.yml`:

```yaml
nginx:
  ports:
    - "8080:80"  # Changer 8000 en 8080

db:
  ports:
    - "3308:3306"  # Changer 3307 en 3308
```

### Modifier la configuration PHP

Éditez `docker/php/php.ini` pour ajuster:
- `upload_max_filesize`
- `post_max_size`
- `memory_limit`
- `max_execution_time`

## 🐛 Dépannage

### Les conteneurs ne démarrent pas

```bash
docker-compose down
docker-compose up -d --force-recreate
```

### Problèmes de permissions

```bash
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R laravel:laravel storage bootstrap/cache
```

### Réinitialiser complètement

```bash
docker-compose down -v
docker-compose up -d
# Puis réexécuter les étapes d'installation
```

## 📦 Structure des services

- **app**: Conteneur PHP-FPM avec Laravel
- **nginx**: Serveur web
- **db**: Base de données MySQL 8.0

## 🔒 Production

Pour la production, modifiez `.env`:

```env
APP_ENV=production
APP_DEBUG=false
```

Et ajoutez des secrets sécurisés pour les mots de passe de base de données.
