# Guide de Sécurité CI/CD

## 🔒 Mesures de sécurité implémentées

### 1. **Scan des vulnérabilités**
- ✅ Composer Audit (dépendances PHP)
- ✅ Symfony Security Checker
- ✅ NPM Audit (dépendances JavaScript)

### 2. **Analyse statique (SAST)**
- ✅ PHPStan (niveau 5+)
- ✅ PHP CodeSniffer (PSR-12)
- ✅ PHP Mess Detector
- ✅ PHP Copy/Paste Detector

### 3. **Détection de secrets**
- ✅ Gitleaks
- ✅ TruffleHog
- ✅ Vérification manuelle des fichiers sensibles

### 4. **Tests de sécurité Laravel**
- ✅ Vérification APP_DEBUG=false
- ✅ Vérification APP_KEY présente
- ✅ Tests unitaires et fonctionnels
- ✅ Enlightn Security Checker

### 5. **Permissions et configurations**
- ✅ Permissions 755 (pas 777!)
- ✅ Vérification .env non commité
- ✅ Cache des configurations

## 🛡️ Bonnes pratiques supplémentaires

### Variables d'environnement sécurisées
```yaml
# Utiliser GitHub Secrets pour les données sensibles
env:
  DB_PASSWORD: ${{ secrets.DB_PASSWORD }}
  API_KEY: ${{ secrets.API_KEY }}
```

### Permissions minimales
```yaml
permissions:
  contents: read        # Lecture seule du code
  security-events: write # Écriture des alertes de sécurité
  pull-requests: write  # Commentaires sur les PR
```

### Isolation des jobs
- Chaque job s'exécute dans un environnement isolé
- Les secrets ne sont accessibles qu'aux jobs autorisés
- Utiliser `needs:` pour définir les dépendances

### Scan des images Docker (si applicable)
```yaml
- name: Scan Docker image
  uses: aquasecurity/trivy-action@master
  with:
    image-ref: 'myapp:latest'
    format: 'sarif'
    output: 'trivy-results.sarif'
```

## 📋 Checklist de sécurité

### Avant chaque commit
- [ ] Pas de secrets dans le code
- [ ] .env dans .gitignore
- [ ] Dépendances à jour
- [ ] Tests passent

### Avant chaque déploiement
- [ ] APP_DEBUG=false
- [ ] APP_ENV=production
- [ ] Clés SSL/TLS valides
- [ ] Backup de la base de données
- [ ] Logs de sécurité activés

### Configuration Laravel sécurisée

#### .env.production
```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:...

# HTTPS obligatoire
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=strict

# Protection CSRF
SANCTUM_STATEFUL_DOMAINS=yourdomain.com
```

#### config/session.php
```php
'secure' => env('SESSION_SECURE_COOKIE', true),
'http_only' => true,
'same_site' => 'strict',
```

#### Headers de sécurité (middleware)
```php
// app/Http/Middleware/SecurityHeaders.php
public function handle($request, Closure $next)
{
    $response = $next($request);
    
    $response->headers->set('X-Frame-Options', 'DENY');
    $response->headers->set('X-Content-Type-Options', 'nosniff');
    $response->headers->set('X-XSS-Protection', '1; mode=block');
    $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
    
    return $response;
}
```

## 🚨 Gestion des incidents

### En cas de fuite de secrets
1. Révoquer immédiatement les credentials
2. Générer de nouvelles clés
3. Analyser les logs d'accès
4. Notifier l'équipe de sécurité

### Rapporter une vulnérabilité
Envoyez un email à: security@votredomaine.com

## 📚 Ressources

- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [Laravel Security Best Practices](https://laravel.com/docs/security)
- [GitHub Security Best Practices](https://docs.github.com/en/actions/security-guides)
