# 📬 Exemples de requêtes API

Tous les exemples ci-dessous utilisent curl. Vous pouvez aussi utiliser Postman, Insomnia, ou toute autre plateforme.

## 🔐 Phase 1 : Authentification

### 1.1 - Enregistrement d'un utilisateur "User"

```bash
curl -X POST http://localhost:8000/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "username": "jean_martin",
    "last_name": "Martin",
    "first_name": "Jean",
    "password": "MonMotDePasse123!",
    "email": "jean.martin@example.com",
    "user_type": "User"
  }'
```

**Réponse (201) :**
```json
{
  "id": 1,
  "token": "a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6",
  "message": "User registered successfully. Please verify your email."
}
```

Sauvegardez le `token` pour les prochaines étapes.

---

### 1.2 - Enregistrement d'un administrateur

```bash
curl -X POST http://localhost:8000/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "username": "admin_sophie",
    "last_name": "Dubois",
    "first_name": "Sophie",
    "password": "AdminPass123!",
    "email": "sophie.admin@example.com",
    "user_type": "Admin"
  }'
```

Sauvegardez aussi ce token (pour tester les routes admin).

---

### 1.3 - Vérifier l'email (PATCH)

```bash
curl -X PATCH http://localhost:8000/auth/verify-email \
  -H "Content-Type: application/json" \
  -d '{
    "token": "a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6"
  }'
```

**Réponse (200) :**
```json
{
  "message": "Email verified successfully"
}
```

---

### 1.4 - Se connecter (obtenir un nouveau token)

```bash
curl -X POST http://localhost:8000/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "jean.martin@example.com",
    "password": "MonMotDePasse123!"
  }'
```

**Réponse (200) :**
```json
{
  "token": "new_token_after_login_b1c2d3e4f5g6h7i8j9",
  "user_id": 1,
  "message": "Logged in successfully"
}
```

---

## 📊 Phase 2 : Gestion des projets

### 2.1 - Créer un projet

```bash
TOKEN="a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6"

curl -X POST http://localhost:8000/projects \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Application Mobile E-Commerce",
    "requested_budget": "15000.00",
    "illustration_path": "/images/ecommerce-app.jpg"
  }'
```

**Réponse (201) :**
```json
{
  "id": 1,
  "name": "Application Mobile E-Commerce",
  "message": "Project created successfully"
}
```

---

### 2.2 - Créer un deuxième projet

```bash
curl -X POST http://localhost:8000/projects \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Site Web Corporate",
    "requested_budget": "8500.50",
    "illustration_path": "/images/corporate.jpg"
  }'
```

---

### 2.3 - Lister tous les projets

```bash
curl -X GET "http://localhost:8000/projects" \
  -H "Authorization: Bearer $TOKEN"
```

**Réponse (200) :**
```json
{
  "total": 2,
  "projects": [
    {
      "id": 1,
      "name": "Application Mobile E-Commerce",
      "requested_budget": "15000.00",
      "allocated_budget": null,
      "illustration_path": "/images/ecommerce-app.jpg",
      "creation_date": "2026-04-09 14:23:45",
      "status": "Pending",
      "requester": "jean_martin",
      "approver": null
    },
    {
      "id": 2,
      "name": "Site Web Corporate",
      "requested_budget": "8500.50",
      "allocated_budget": null,
      "illustration_path": "/images/corporate.jpg",
      "creation_date": "2026-04-09 14:25:10",
      "status": "Pending",
      "requester": "jean_martin",
      "approver": null
    }
  ]
}
```

---

### 2.4 - Lister les projets avec filtres

#### Filtrer par nom :
```bash
curl -X GET "http://localhost:8000/projects?name=Mobile" \
  -H "Authorization: Bearer $TOKEN"
```

#### Filtrer par status :
```bash
curl -X GET "http://localhost:8000/projects?status=Pending" \
  -H "Authorization: Bearer $TOKEN"
```

#### Filtrer par date de création (après) :
```bash
curl -X GET "http://localhost:8000/projects?created_after=2026-04-08" \
  -H "Authorization: Bearer $TOKEN"
```

#### Filtrer par requester :
```bash
curl -X GET "http://localhost:8000/projects?requester_name=jean" \
  -H "Authorization: Bearer $TOKEN"
```

#### Combiner plusieurs filtres :
```bash
curl -X GET "http://localhost:8000/projects?status=Pending&name=Mobile&requester_name=jean_martin" \
  -H "Authorization: Bearer $TOKEN"
```

---

### 2.5 - Mettre à jour un projet (PUT)

Seul le créateur du projet peut le modifier :

```bash
curl -X PUT http://localhost:8000/projects/1 \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Application Mobile E-Commerce v2",
    "requested_budget": "18000.00"
  }'
```

**Réponse (200) :**
```json
{
  "message": "Project updated successfully"
}
```

---

### 2.6 - Rejoindre un projet (attendee)

```bash
TOKEN="another_user_token"

curl -X POST http://localhost:8000/projects/1/join \
  -H "Authorization: Bearer $TOKEN"
```

**Réponse (200) :**
```json
{
  "message": "Joined project successfully"
}
```

---

### 2.7 - Supprimer un projet (DELETE)

Seul le créateur peut supprimer :

```bash
curl -X DELETE http://localhost:8000/projects/1 \
  -H "Authorization: Bearer $TOKEN"
```

**Réponse (204) :**
```
(pas de contenu)
```

---

## 👨‍💼 Phase 3 : Gestion Admin

### 3.1 - Approuver un projet et allouer un budget

Seul un admin peut faire cela :

```bash
ADMIN_TOKEN="admin_token_saved_earlier"

curl -X PATCH http://localhost:8000/admin/projects/2 \
  -H "Authorization: Bearer $ADMIN_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "status": "Approved",
    "allocated_budget": "8000.00"
  }'
```

**Réponse (200) :**
```json
{
  "message": "Project updated successfully"
}
```

---

### 3.2 - Changer seulement le status

```bash
curl -X PATCH http://localhost:8000/admin/projects/2 \
  -H "Authorization: Bearer $ADMIN_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "status": "In Progress"
  }'
```

---

### 3.3 - Allouer uniquement un budget

```bash
curl -X PATCH http://localhost:8000/admin/projects/2 \
  -H "Authorization: Bearer $ADMIN_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "allocated_budget": "7500.00"
  }'
```

---

## ❌ Exemples d'erreurs

### Essayer de créer un projet sans token

```bash
curl -X POST http://localhost:8000/projects \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test",
    "requested_budget": "1000",
    "illustration_path": "/test"
  }'
```

**Réponse (401) :**
```json
{
  "error": "Not logged in"
}
```

---

### Essayer de modifier un projet que tu n'as pas créé

```bash
curl -X PUT http://localhost:8000/projects/2 \
  -H "Authorization: Bearer $ANOTHER_USER_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"name": "Hacked!"}'
```

**Réponse (403) :**
```json
{
  "error": "Not the owner"
}
```

---

### Essayer d'accéder comme admin sans les droits

```bash
curl -X PATCH http://localhost:8000/admin/projects/2 \
  -H "Authorization: Bearer $REGULAR_USER_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"status": "Completed"}'
```

**Réponse (403) :**
```json
{
  "error": "Not Admin"
}
```

---

### Adresse email invalide à l'enregistrement

```bash
curl -X POST http://localhost:8000/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "username": "test",
    "last_name": "User",
    "first_name": "Test",
    "password": "pass123",
    "email": "invalid-email",
    "user_type": "User"
  }'
```

**Réponse (400) :**
```json
{
  "error": "Invalid email format"
}
```

---

### Adresse email déjà existante

```bash
curl -X POST http://localhost:8000/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "username": "another_user",
    "last_name": "Dupont",
    "first_name": "Marc",
    "password": "pass123",
    "email": "jean.martin@example.com",
    "user_type": "User"
  }'
```

**Réponse (409) :**
```json
{
  "error": "Email already exists"
}
```

---

## 🔑 Conservation des tokens

Pour tester les routes protégées, sauvegardez les tokens dans des variables :

```bash
# Après l'enregistrement
USER_TOKEN="a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6"
ADMIN_TOKEN="z9y8x7w6v5u4t3s2r1q0p9o8n7m6l5k4"

# Réutilisez-les dans d'autres commandes
curl -X GET "http://localhost:8000/projects" \
  -H "Authorization: Bearer $USER_TOKEN"
```

---

## 📊 Scénario de test complet recommandé

1. ✅ Enregistrer 2 utilisateurs (1 regular + 1 admin)
2. ✅ Vérifier leur email
3. ✅ Se connecter avec chacun
4. ✅ Créer des projets avec chacun
5. ✅ Tester les filtres GET
6. ✅ Modifier un projet
7. ✅ Un utilisateur rejoint un projet
8. ✅ L'admin approuve et alloue un budget
9. ✅ Tester les cas d'erreur

Bonne chance ! 🚀
