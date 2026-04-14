# 📚 Documentation du projet

## 📋 Documentation disponible

### Démarrage
- **[README.md](README.md)** - Documentation principale et guide de démarrage rapide
- **[CONTRIBUTING.md](CONTRIBUTING.md)** - Directives de contribution et flux de travail de développement
- **[SECURITY.md](SECURITY.md)** - Directives de sécurité et liste de contrôle du déploiement

### Backend
- **[backend/README.md](backend/README.md)** - Documentation de l'API Backend
- **[backend/API_ROUTES.md](backend/API_ROUTES.md)** - Référence complète des routes API
- **[backend/EXEMPLES_REQUETES.md](backend/EXEMPLES_REQUETES.md)** - Exemples de requêtes
- **[backend/IMPLEMENTATION_SUMMARY.md](backend/IMPLEMENTATION_SUMMARY.md)** - Aperçu de l'implémentation

### Frontend
- **[frontend/README.md](frontend/README.md)** - Documentation Frontend React

## 🚀 Liens rapides

### URLs de développement local
- 🏠 Backend : http://localhost:8000
- 🔧 EasyAdmin : http://localhost:8000/admin
- 📊 API : http://localhost:8000/api
- 🎨 Frontend : http://localhost:5173

### Endpoints API
- Utilisateurs : http://localhost:8000/api/users
- Projets : http://localhost:8000/api/projects
- Statuts : http://localhost:8000/api/project_statuses
- Types d'utilisateurs : http://localhost:8000/api/user_types

## 🛠️ Commandes courantes

### Docker
```bash
# Démarrer tous les services
docker compose up -d --build

# Arrêter tous les services
docker compose down

# Afficher les journaux
docker compose logs -f [service]

# Exécuter une commande dans un conteneur
docker compose exec [service] [command]
```

### Backend (PHP/Symfony)
```bash
# Créer une migration
docker compose exec backend php bin/console make:migration

# Exécuter les migrations
docker compose exec backend php bin/console doctrine:migrations:migrate

# Effacer le cache
docker compose exec backend php bin/console cache:clear

# Charger les fixtures
docker compose exec backend php bin/console doctrine:fixtures:load

# Accéder à la base de données
docker compose exec database mysql -u corpstarter -p corpstarter
```

### Frontend (Node/React)
```bash
# Installer les dépendances
docker compose exec frontend npm install

# Construire
docker compose exec frontend npm run build

# Linter
docker compose exec frontend npm run lint
```

## 📁 Project Structure

```
CorpStarter/
├── backend/
│   ├── EasyAdmin/          # Main Symfony application
│   │   ├── src/
│   │   │   ├── Entity/     # Doctrine entities
│   │   │   ├── Controller/ # HTTP controllers
│   │   │   ├── ApiResource/# API Platform resources
│   │   │   └── ...
│   │   ├── config/         # Configuration files
│   │   ├── migrations/     # Database migrations
│   │   ├── public/         # Web root
│   │   ├── templates/      # Twig templates
│   │   ├── tests/          # Unit tests
│   │   └── docker/         # Docker files
│   ├── src/                # Shared code (copy to EasyAdmin)
│   ├── vendor/             # PHP dependencies
│   ├── README.md           # Backend documentation
│   ├── API_ROUTES.md       # API documentation
│   └── compose.yaml        # Backend-specific compose
├── frontend/
│   ├── src/
│   │   ├── App.jsx         # Main component
│   │   ├── main.jsx        # Entry point
│   │   ├── api/            # API services
│   │   ├── components/     # React components
│   │   ├── pages/          # Page components
│   │   └── ...
│   ├── public/             # Static assets
│   ├── node_modules/       # JS dependencies
│   ├── package.json        # Dependencies
│   ├── vite.config.js      # Vite config
│   ├── tailwind.config.js  # Tailwind config
│   └── README.md           # Frontend documentation
├── docker-compose.yml      # Main compose file
├── README.md               # Main documentation
├── CONTRIBUTING.md         # Contribution guidelines
├── SECURITY.md             # Security guidelines
└── ...
```

## 🗃️ Database Schema

### Tables
- `users` - User accounts
- `user_types` - User role types
- `projects` - Project records
- `project_status` - Project status types
- `project_users` - Many-to-many junction table

### Key Relationships
- User → UserType (ManyToOne)
- User → Projects (ManyToMany, requester)
- User → Projects (OneToMany, approver)
- Project → ProjectStatus (ManyToOne)

## 🔌 API Platform

API endpoints are automatically generated from Doctrine entities with `#[ApiResource]`.

### Available Formats
- JSON (default)
- JSON-LD
- HAL+JSON

### Filtering & Pagination
- Supported automatically for most endpoints
- Query parameters: `?page=1&itemsPerPage=30`
- Filtering: `?property[]=value`

## 🧪 Testing

### Backend Testing
```bash
# Run tests
docker compose exec backend php bin/console test

# With coverage
docker compose exec backend php -pcov ./vendor/bin/phpunit --coverage-html ./coverage
```

### Frontend Testing
```bash
# Run tests (when configured)
docker compose exec frontend npm run test
```

## 🐛 Troubleshooting

### Backend Service Won't Start
```bash
# Check logs
docker compose logs backend

# Clear cache and try again
docker compose exec backend php bin/console cache:clear
docker compose restart backend
```

### Port Already in Use
```bash
# Change in docker-compose.yml
ports:
  - "8001:80"  # Use 8001 instead
```

### Database Connection Error
```bash
# Verify database service is running
docker compose ps

# Check DATABASE_URL in .env
docker compose exec backend echo $DATABASE_URL
```

## 📞 Getting Help

- 📖 Check documentation files
- 🐛 GitHub Issues (for bugs)
- 💬 GitHub Discussions (for questions)
- 📧 Email: support@corpstarter.com

## 📄 License

CorpStarter - All rights reserved

---

**Last Updated:** April 14, 2026
