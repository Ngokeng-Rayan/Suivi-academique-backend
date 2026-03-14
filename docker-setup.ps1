# Script de configuration Docker pour Windows
Write-Host "🚀 Configuration de l'application Laravel avec Docker..." -ForegroundColor Green

# Copier le fichier .env si nécessaire
if (-not (Test-Path .env)) {
    Write-Host "📝 Copie du fichier .env.example vers .env" -ForegroundColor Yellow
    Copy-Item .env.example .env
}

# Arrêter les conteneurs existants
Write-Host "🛑 Arrêt des conteneurs existants..." -ForegroundColor Yellow
docker-compose down

# Construire et démarrer les conteneurs
Write-Host "🐳 Construction des images Docker..." -ForegroundColor Cyan
docker-compose build

Write-Host "🚀 Démarrage des conteneurs..." -ForegroundColor Cyan
docker-compose up -d

# Attendre que MySQL soit prêt
Write-Host "⏳ Attente du démarrage de MySQL (30 secondes)..." -ForegroundColor Yellow
Start-Sleep -Seconds 30

# Vérifier que MySQL est prêt
Write-Host "🔍 Vérification de la connexion MySQL..." -ForegroundColor Cyan
$maxRetries = 10
$retryCount = 0
$mysqlReady = $false

while (-not $mysqlReady -and $retryCount -lt $maxRetries) {
    $retryCount++
    Write-Host "Tentative $retryCount/$maxRetries..." -ForegroundColor Gray
    
    $result = docker-compose exec -T db mysqladmin ping -h localhost -u root -proot 2>&1
    if ($LASTEXITCODE -eq 0) {
        $mysqlReady = $true
        Write-Host "✅ MySQL est prêt!" -ForegroundColor Green
    } else {
        Start-Sleep -Seconds 3
    }
}

if (-not $mysqlReady) {
    Write-Host "❌ MySQL n'est pas prêt après $maxRetries tentatives" -ForegroundColor Red
    Write-Host "Vérifiez les logs avec: docker-compose logs db" -ForegroundColor Yellow
    exit 1
}

# Installation des dépendances
Write-Host "📦 Installation des dépendances Composer..." -ForegroundColor Cyan
docker-compose exec app composer install

# Générer la clé d'application
Write-Host "🔑 Génération de la clé d'application..." -ForegroundColor Cyan
docker-compose exec app php artisan key:generate

# Créer le lien de stockage
Write-Host "🔗 Création du lien de stockage..." -ForegroundColor Cyan
docker-compose exec app php artisan storage:link

# Exécuter les migrations
Write-Host "🗄️  Exécution des migrations..." -ForegroundColor Cyan
docker-compose exec app php artisan migrate --force

# Définir les permissions
Write-Host "🔐 Configuration des permissions..." -ForegroundColor Cyan
docker-compose exec app chmod -R 775 storage bootstrap/cache

Write-Host ""
Write-Host "✅ Configuration terminée!" -ForegroundColor Green
Write-Host "🌐 L'application est accessible sur: http://localhost:8000" -ForegroundColor Cyan
Write-Host "🗄️  MySQL est accessible sur: localhost:3307" -ForegroundColor Cyan
Write-Host ""
Write-Host "Commandes utiles:" -ForegroundColor Yellow
Write-Host "  docker-compose logs -f        # Voir les logs"
Write-Host "  docker-compose down           # Arrêter les conteneurs"
Write-Host "  docker-compose restart        # Redémarrer les conteneurs"
