# 🚀 Guide de Démarrage - CorpStarter 

Bienvenue dans la documentation officielle de notre infrastructure. 
Pour simplifier le développement et éviter les bugs, nous utilisons Docker avec une architecture **Monorepo** (le Front et le Back sont dans le même dossier).


## 🛠️ Étape 1 : Installation des prérequis
Vous n'avez **PAS** besoin d'installer PHP, Node.js ou MySQL sur votre ordinateur. Docker s'occupe de tout. Vous avez juste besoin de :

1. **Git**
2. **Docker (selon votre machine) :**
   * 🪟 **Windows / Mac Intel :** Installez [Docker Desktop](https://www.docker.com/products/docker-desktop/).
   * 🍎 **Mac M1/M2/M3/M4 (Apple Silicon) :** 🚨 N'utilisez pas Docker Desktop ! Installez [OrbStack](https://orbstack.dev/). C'est une alternative plus légère qui corrige nativement tous les bugs de compatibilité processeur avec les bases de données.

---

## 🚀 Étape 2 : Lancer le projet sur votre machine

Ouvrez votre terminal et suivez ces instructions dans l'ordre :

**1. Récupérer le code source :**
```bash
git clone [https://github.com/CorpStarter/CorpStarter.git](https://github.com/CorpStarter/CorpStarter.git)
cd CorpStarter
```
**2. Démarrer la magie (Serveurs virtuels) :**
```bash
docker compose up -d --build
(Patientez 2 à 3 minutes la première fois, le temps que l'environnement se télécharge et s'installe).
```

**3. Vérifier que tout fonctionne dans le navigateur :**
```bash
🎨 Frontend (React) : http://localhost:5173

⚙️ Backend (API/Symfony) : http://localhost:8000
```

## 💻 Étape 4 : Comment coder au quotidien avec Docker ?

### 🧠 Comprendre la logique (La règle d'or)
Avec Docker, **vous continuez d'écrire votre code normalement** sur votre ordinateur (avec VS Code ou PhpStorm). Dès que vous sauvegardez un fichier, il est synchronisé instantanément à l'intérieur de Docker. 

Cependant, vos outils (`php`, `composer`, `npm`) ne sont pas installés sur votre ordinateur, mais **à l'intérieur** des conteneurs virtuels. 
👉 Vous ne pouvez donc pas lancer de commandes directement dans le terminal de votre PC. Vous devez d'abord "entrer" virtuellement dans le conteneur.

---

### 🐘 Pour l'équipe Backend (Symfony)

Si vous devez créer un contrôleur, une entité, ou installer un bundle Symfony, vous devez agir dans le conteneur `backend` (qui contient PHP 8.3).

1. Entrez dans le serveur Backend :
```bash
docker compose exec backend bash
(Remarquez que votre terminal change d'apparence : vous êtes maintenant à l'intérieur du serveur Linux !)
```
2. Tapez vos commandes Symfony habituelles :
```bash
# Exemples de commandes que vous pouvez taper maintenant :
php bin/console make:controller
php bin/console make:entity Project
composer require form validator
```
3. Ressortir du serveur :
```bash
Quand vous avez fini, tapez simplement exit pour revenir au terminal de votre ordinateur.
```

**⚛️ Pour l'équipe Frontend (React)**
Si vous devez installer une nouvelle librairie JavaScript (ex: Axios, Tailwind, React Router), vous devez agir dans le conteneur frontend (qui contient Node.js).

1. Entrez dans le serveur Frontend :
```bash
docker compose exec frontend sh
```
2. Tapez vos commandes NPM habituelles :
```bash
# Exemples de commandes :
npm install axios
npm install react-router-dom
3. Ressortir du serveur :
Tapez exit pour revenir à votre terminal normal.
```

### 🌅 La routine d'une journée de travail standard

**Voici le cycle de vie de votre projet au quotidien :**

Le Matin (J'allume les moteurs) : Dans le terminal à la racine du projet, tapez : 
```bash
docker compose up -d
```
La Journée (Je code) : Vous modifiez vos fichiers dans VS Code. Le site s'actualise tout seul sur localhost:8000 et localhost:5173. Si vous avez besoin d'une ligne de commande, vous faites un :
```bash
 docker compose exec...
```
Le Soir (J'éteins proprement) : Pour ne pas user la batterie et la RAM de votre PC, éteignez les conteneurs : 
```bash
docker compose down
```