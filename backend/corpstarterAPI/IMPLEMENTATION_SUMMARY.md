# 📋 Résumé de l'implémentation API Platform

## ✅ Qu'est-ce qui a été créé ?

### Services métier (3 fichiers)
- **[AuthService.php](src/Service/AuthService.php)** - Gestion de l'authentification
  - `register()` - Enregistrement utilisateur avec hash de mot de passe
  - `verifyEmail()` - Vérification d'email avec token
  - `login()` - Connexion avec génération de token
  - `validateToken()` - Validation de token pour les routes protégées

- **[ProjectService.php](src/Service/ProjectService.php)** - Gestion des projets
  - `createProject()` - Création de projet avec status "Pending"
  - `getProjects()` - Récupération avec filtres (status, nom, dates, requester, approver)
  - `updateProject()` - Modification de projet (name, budget, illustration)
  - `deleteProject()` - Suppression de projet
  - `joinProject()` - Ajout utilisateur comme attendee

- **[AdminService.php](src/Service/AdminService.php)** - Gestion admin
  - `updateProjectAdmin()` - Mise à jour status et budget (admin uniquement)

### Contrôleurs (3 fichiers)
- **[AuthController.php](src/Controller/AuthController.php)** - Authentification
  - `POST /auth/register` → 201 / 400 / 409
  - `PATCH /auth/verify-email` → 200 / 400 / 404
  - `POST /auth/login` → 200 / 401

- **[ProjectController.php](src/Controller/ProjectController.php)** - Projets
  - `POST /projects` → 201 / 400 / 401
  - `GET /projects` → 200 / 401 (avec filtres optionnels)
  - `PUT /projects/{id}` → 200 / 403 / 404 / 422
  - `DELETE /projects/{id}` → 204 / 403 / 404 / 422
  - `POST /projects/{id}/join` → 200 / 404 / 409

- **[AdminController.php](src/Controller/AdminController.php)** - Administration
  - `PATCH /admin/projects/{id}` → 200 / 400 / 403 / 404

### Configuration
- **[config/routes/controllers.php](config/routes/controllers.php)** - Chargement automatique des contrôleurs
- **config/routes.yaml** - Mise à jour pour inclure la route "api"

### Documentation
- **[API_ROUTES.md](API_ROUTES.md)** - Documentation complète de toutes les routes

---

## 🔐 Authentification

Toutes les routes (sauf `/auth/register`, `/auth/login`, `/auth/verify-email`) nécessitent un token Bearer :

```
Authorization: Bearer {token}
```

Le token est obtenu lors de :
1. L'enregistrement (`/auth/register`)
2. La connexion (`/auth/login`)

---

## 📝 Détails de l'implémentation

### ✨ Fonctionnalités clés

1. **Enregistrement sécurisé**
   - Hash du mot de passe avec `password_hash()` (BCRYPT)
   - Validation du format email
   - Vérification des doublons
   - Génération de token unique
   - Email non confirmé par défaut

2. **Authentification robuste**
   - Vérification du password avec `password_verify()`
   - Contrôle de confirmation d'email
   - Contrôle d'acceptation des conditions
   - Token unique généré à chaque connexion

3. **Gestion des projets**
   - Création avec status "Pending"
   - Modification seulement par le créateur
   - Suppression seulement par le créateur
   - Restriction si le projet est validé
   - Join avec vérification (pas de rejected, pas de doublon)

4. **Gestion admin**
   - Vérification que l'utilisateur est admin (`can_accept_project = true`)
   - Mise à jour du status avec définition du approver
   - Validation du budget (positif)
   - Message d'erreur cohérent avec les codes HTTP spécifiés

### 🔗 Relations utilisées

- **Users** ↔ **UserTypes** (ManyToOne)
- **Project** ↔ **Users** (requester/approver)
- **Project** ↔ **ProjectStatus** (ManyToOne)
- **Project** ↔ **Users** (attendees - ManyToMany)

### 📦 HTTP Status Codes

Tous les codes d'erreur spécifiés sont implémentés :
- **201** - Création réussie
- **200** - Succès
- **204** - Suppression réussie (pas de contenu)
- **400** - Mauvaise requête / Erreur de validation
- **401** - Non authentifié / Identifiants incorrects
- **403** - Accès refusé (Forbidden)
- **404** - Ressource non trouvée
- **409** - Conflit (déjà existant, déjà joint)
- **422** - Entité non traitable (projet validé)

---

## 🚀 Comment tester

### 1. Enregistrement
```bash
POST /auth/register
Content-Type: application/json

{
  "username": "john_doe",
  "last_name": "Doe",
  "first_name": "John",
  "password": "SecurePassword123!",
  "email": "john@example.com",
  "user_type": "User"
}
```

Réponse : Token obtenu

### 2. Vérification d'email
```bash
PATCH /auth/verify-email
Content-Type: application/json

{
  "token": "{token_from_register}"
}
```

### 3. Connexion
```bash
POST /auth/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "SecurePassword123!"
}
```

Réponse : Nouveau token obtenu

### 4. Créer un projet (authentifié)
```bash
POST /projects
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Mon Projet",
  "requested_budget": "5000.00",
  "illustration_path": "/images/project.jpg"
}
```

---

## 📌 Notes importantes

1. ✅ Tous les services utilisent l'injection de dépendances Symfony
2. ✅ Gestion complète des exceptions avec codes HTTP appropriés
3. ✅ Logique métier séparée des contrôleurs
4. ✅ Authentification basée sur les tokens (pas de sessions)
5. ✅ Routes visibles dans la documentation API Platform via OpenAPI/Swagger
6. ✅ Filtrage robuste pour GET /projects
7. ✅ Vérification des propriétés et droits d'accès
8. ✅ Hachage sécurisé des mots de passe

---

## 🔧 Pour déboguer / lister les routes

```bash
php bin/console debug:router
```

Cela affichera toutes les routes enregistrées avec leurs attributs (méthode, endpoint, contrôleur).

---

## 📚 Documentation OpenAPI/Swagger

Les routes ont des commentaires OpenAPI (#[OA\\...]) pour l'auto-génération de documentation.
Consultez: `https://localhost/api/docs` (selon votre configuration API Platform)
