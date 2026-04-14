# 🔒 Directives de sécurité et de déploiement

## ⚠️ PROBLÈMES DE SÉCURITÉ CRITIQUES

### Problèmes actuels (développement uniquement)

1. **Mot de passe MySQL en texte brut**
   - Localisation : `docker-compose.yml`
   - Actuel : `MYSQL_PASSWORD: "Po@rmsoigshknn549073"`
   - ⚠️ **JAMAIS** utiliser en production

2. **Pas d'authentification sur l'API**
   - API Platform n'a pas d'authentification configurée
   - Tous les endpoints sont accessibles publiquement
   - ⚠️ Implémentez JWT ou OAuth2 avant la production

3. **Symfony APP_SECRET**
   - Situé dans `backend/EasyAdmin/.env`
   - Doit être modifié par environnement
   - ⚠️ Générez avec : `php bin/console secrets:generate-keys`

## 🔐 Avant le déploiement en production

### 1. Secrets d'environnement
```bash
# Générez de nouvelles clés secrètes
docker compose exec backend php bin/console secrets:generate-keys

# Modifiez tous les mots de passe par défaut
# - Mot de passe MySQL
# - APP_SECRET de Symfony
# - Tokens API
```

### 2. Sécurité de la base de données
```dockerfile
# docker-compose.yml
MYSQL_ROOT_PASSWORD: ${DB_ROOT_PASSWORD}
MYSQL_PASSWORD: ${DB_PASSWORD}
MYSQL_USER: ${DB_USER}
```

### 3. Authentification API
Ajoutez à `backend/EasyAdmin/src/Entity/Users.php`:
```php
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
public function getProjects()
{
    // Endpoint protégé
}
```

### 4. Configuration CORS
Mettez à jour `nelmio/cors-bundle` dans `backend/EasyAdmin/config/packages/nelmio_cors.yaml`:
```yaml
nelmio_cors:
  defaults:
    allow_credentials: true
    allow_origin: ['https://votredomaine.com']
    allow_headers: ['*']
    allow_methods: ['GET', 'POST', 'PUT', 'DELETE']
```

### 5. HTTPS/SSL
- Utilisez un proxy inverse (Nginx, Traefik, ou AWS ALB)
- Utilisez toujours HTTPS en production
- Mettez à jour l'URL de l'API frontend en HTTPS

## 🚀 Liste de contrôle du déploiement en production

- [ ] Modifiez tous les mots de passe par défaut
- [ ] Générez de nouveaux secrets Symfony
- [ ] Activez l'authentification API (JWT/OAuth2)
- [ ] Configurez CORS correctement
- [ ] Configurez HTTPS/SSL
- [ ] Configurez les sauvegardes de la base de données
- [ ] Configurez la surveillance/les journaux
- [ ] Configurez la limitation de débit
- [ ] Définissez les variables d'environnement de manière sécurisée
- [ ] Désactivez le mode debug (`APP_ENV=prod`)
- [ ] Examinez les en-têtes de sécurité

## 📦 Variables d'environnement

### Backend (.env)
```bash
APP_ENV=prod  # PAS dev
APP_SECRET=votre-cle-secrete
DATABASE_URL="mysql://user:password@hostname:3306/dbname"
CORS_ALLOW_ORIGIN='^https://votredomaine\.com$'
```

### Frontend (.env)
```bash
VITE_API_URL=https://api.votredomaine.com
VITE_DEBUG=false  # PAS true en production
```

## 🐳 Sécurité Docker

### Bonnes pratiques
1. Ne lancez pas les conteneurs en tant que root
2. Utilisez des systèmes de fichiers en lecture seule si possible
3. Analysez les images pour les vulnérabilités
4. Maintenez les images Docker à jour
5. Utilisez la gestion des secrets (Docker Secrets, Vault)

### Exemple de docker-compose.yml sécurisé
```yaml
services:
  backend:
    build: ./backend/EasyAdmin
    environment:
      DATABASE_URL: ${DATABASE_URL}
      APP_SECRET: ${APP_SECRET}
    volumes:
      - ./backend/EasyAdmin:/var/www/html:ro  # Lecture seule
    ports:
      - "127.0.0.1:8000:80"  # Localhost uniquement
    restart: always
    healthcheck:
      test: ["CMD", "curl", "-f", "http://localhost/"]
      interval: 30s
      timeout: 10s
      retries: 3
```

## 🔑 Gestion des identifiants

### Identifiants de production
- Utilisez les variables d'environnement
- Utilisez Docker Secrets (Docker Compose/Swarm)
- Utilisez AWS Secrets Manager
- Utilisez Vault ou des outils similaires
- **JAMAIS** validez les identifiants

### Exemple avec .env.local
```bash
# .env.local (NON validé)
DATABASE_PASSWORD=votre-mot-de-passe-securise
APP_SECRET=votre-cle-secrete-ici
```

## 📊 Surveillance et journalisation

Configuration pour la production :
1. **Journaux Docker** : `docker compose logs -f backend`
2. **Journalisation centralisée** : ELK Stack, Loki, CloudWatch
3. **Vérifications de santé** : Configurez les vérifications de santé Docker
4. **Surveillance** : Prometheus, Grafana, DataDog
5. **Suivi des erreurs** : Sentry, Rollbar

## 🔔 Alertes

Configurez des alertes pour :
- Défaillances de connexion à la base de données
- Erreurs API (500, 502, 503)
- Utilisation élevée du CPU/Mémoire
- Avertissements d'espace disque
- Sauvegardes échouées

## 🔄 Sauvegarde et récupération

Sauvegardes quotidiennes :
```bash
# Sauvegarde MySQL
docker compose exec database mysqldump \
  -u${DB_USER} -p${DB_PASSWORD} \
  ${DB_NAME} > backup_$(date +%Y%m%d).sql
```

Testez régulièrement la récupération !

## 📞 Support

Pour les problèmes de sécurité :
1. **NE publiez PAS** de problèmes publics
2. Email : security@corpstarter.com
3. Utilisez GitHub Security Advisory

---

**Souvenez-vous : la sécurité est la responsabilité de chacun ! 🛡️**
