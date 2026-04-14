# 🤝 Contribution à CorpStarter

Merci de vouloir contribuer à **CorpStarter** ! Nous apprécions toutes les contributions, qu'elles soient des rapports de bogues, des demandes de fonctionnalités, des améliorations de documentation ou des contributions de code.

## 📋 Table des matières

- [Code de conduite](#code-de-conduite)
- [Démarrage](#démarrage)
- [Flux de travail de développement](#flux-de-travail-de-développement)
- [Flux de travail Git](#flux-de-travail-git)
- [Messages de commit](#messages-de-commit)
- [Directives pour les Pull Requests](#directives-pour-les-pull-requests)
- [Signaler des bogues](#signaler-des-bogues)
- [Demandes de fonctionnalités](#demandes-de-fonctionnalités)

## Code de conduite

Soyez respectueux et professionnel dans toutes les interactions. Nous nous engageons à fournir un environnement accueillant et inclusif.

## Démarrage

### 1. Fork le dépôt
```bash
Cliquez sur "Fork" sur GitHub
```

### 2. Clonez votre Fork
```bash
git clone https://github.com/YOUR_USERNAME/CorpStarter.git
cd CorpStarter
```

### 3. Ajoutez le remote Upstream
```bash
git remote add upstream https://github.com/CorpStarter/CorpStarter.git
```

### 4. Configurez l'environnement de développement
```bash
docker compose up -d --build
```

## Flux de travail de développement

### Développement du Backend (PHP/Symfony)

1. Apportez vos modifications dans le répertoire `backend/EasyAdmin/src/`
2. Testez localement :
   ```bash
   docker compose exec backend php bin/console doctrine:migrations:migrate
   docker compose exec backend php bin/console cache:clear
   ```
3. Accédez à l'API : http://localhost:8000/api

### Développement du Frontend (React/Vite)

1. Apportez des modifications dans `frontend/src/`
2. Hot Module Replacement (HMR) recharge automatiquement les modifications
3. Accédez à l'interface : http://localhost:5173

## Flux de travail Git

### 1. Créez une branche de fonctionnalité
```bash
git checkout -b feature/votre-nom-de-fonctionnalite
# ou
git checkout -b fix/nom-du-bogue
```

### 2. Apportez vos modifications
- Écrivez du code propre et lisible
- Suivez le style de code existant
- Ajoutez des commentaires pour la logique complexe

### 3. Validez vos modifications
```bash
git add .
git commit -m "feat: ajouter l'authentification utilisateur"
```

## Messages de commit

Suivez le format **Conventional Commits** :

```
<type>(<portée>): <sujet>

<corps>

<pied de page>
```

### Types
- **feat** : Nouvelle fonctionnalité
- **fix** : Correction de bogue
- **docs** : Changements de documentation
- **style** : Changements de style de code (formatage, points-virgules manquants, etc)
- **refactor** : Refactorisation sans changement de fonctionnalité
- **perf** : Améliorations de performance
- **test** : Ajout ou mise à jour de tests
- **chore** : Processus de construction, dépendances, etc

### Exemples
```
feat(auth): ajouter l'endpoint de connexion
fix(user): corriger l'algorithme de hachage de mot de passe
docs(api): mettre à jour la documentation de l'API
```

## Directives pour les Pull Requests

### Avant de soumettre

1. **Synchronisez avec upstream**
   ```bash
   git fetch upstream
   git rebase upstream/main
   ```

2. **Créez une PR descriptive**
   - Titre clair expliquant la modification
   - Description de ce que et pourquoi
   - Lien aux problèmes associés : `Fixes #123`
   - Captures d'écran pour les changements UI

3. **Assurez la qualité**
   - Le code se construit sans erreurs
   - Fonctionne localement : `docker compose up -d`
   - Pas d'erreurs/avertissements dans la console
   - Les tests passent (le cas échéant)

### Modèle de PR

```markdown
## Description
Brève description des modifications

## Type de changement
- [ ] Correction de bogue
- [ ] Nouvelle fonctionnalité
- [ ] Changement avec rupture de compatibilité
- [ ] Mise à jour de la documentation

## Tests
- [ ] Testé localement
- [ ] Les endpoints API fonctionnent
- [ ] Pas de régressions

## Captures d'écran (le cas échéant)
...

## Liste de contrôle
- [ ] Le style de code suit les directives du projet
- [ ] Aucune donnée sensible n'est validée
- [ ] Les messages de commit sont clairs
```

## Signaler des bogues

### Avant d'ouvrir un problème

1. Vérifiez si le bogue existe déjà
2. Essayez de le reproduire avec un environnement Docker frais :
   ```bash
   docker compose down -v  # Supprimez les volumes
   docker compose up -d --build
   ```

### Modèle de rapport de bogue

```markdown
## Description
Description claire du bogue

## Étapes pour reproduire
1. ...
2. ...
3. ...

## Comportement attendu
Ce qui devrait se passer

## Comportement réel
Ce qui s'est réellement passé

## Environnement
- OS : Windows/Mac/Linux
- Version Docker : 
- Navigateur : (si problème frontend)

## Captures d'écran
Le cas échéant
```

## Demandes de fonctionnalités

### Suggérer une amélioration

```markdown
## Description
Description claire de la fonctionnalité

## Cas d'usage
Pourquoi cette fonctionnalité est-elle nécessaire ?

## Solution proposée
Comment devrait-elle fonctionner ?

## Alternatives
Autres approches possibles ?
```

## Directives de style de code

### PHP (Backend)
- Norme de codage PSR-12
- Indices de type pour toutes les fonctions
- Utilisez `declare(strict_types=1);`

### JavaScript/React (Frontend)
- Utilisez les fonctionnalités ES6+
- Composants fonctionnels préférés
- Utilisez les hooks pour la gestion d'état
- Conventions de nommage appropriées

### Général
- Créez des commits atomiques
- Ne validez pas les fichiers .env
- Ne validez pas vendor/ ni node_modules/
- Écrivez du code auto-documenté

## Questions ?

- 📧 Email : support@corpstarter.com
- 💬 Discussions : Discussions GitHub
- 📚 Docs : Voir les fichiers README.md dans chaque répertoire

## Licence

En contribuant, vous acceptez que vos contributions seront concédées sous licence selon le fichier LICENSE du projet.

---

**Merci de contribuer à CorpStarter ! 🚀**
