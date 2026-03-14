#!/bin/bash

echo "🚀 Configuration de l'application Laravel avec Docker..."

# Copier le fichier .env si nécessaire
if [ ! -f .env ]; then
    echo "📝 Copie du fichier .env.example vers .env"
    cp .env.example .env
fi

# Construire et démarrer les conteneurs
echo "🐳 Construction des images Docker..."
docker-compose build

echo "🚀 Démarrage des conteneurs..."
docker-compose up -d

# Attendre que MySQL soit prêt
echo "⏳ Attente du démarrage de MySQL..."
sleep 10

# Installation des dépendances
echo "📦 Installation des dépendances Composer..."
docker-compose exec app composer install

# Générer la clé d'application
echo "🔑 Génération de la clé d'application..."
docker-compose exec app php artisan key:generate

# Créer le lien de stockage
echo "🔗 Création du lien de stockage..."
docker-compose exec app php artisan storage:link

# Exécuter les migrations
echo "🗄️  Exécution des migrations..."
docker-compose exec app php artisan migrate --force

# Définir les permissions
echo "🔐 Configuration des permissions..."
docker-compose exec app chmod -R 775 storage bootstrap/cache

echo "✅ Configuration terminée!"
echo "🌐 L'application est accessible sur: http://localhost:8000"
echo "🗄️  MySQL est accessible sur: localhost:3307"
