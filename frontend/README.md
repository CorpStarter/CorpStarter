# 🎨 Frontend CorpStarter - React + Vite

Bienvenue dans le **Frontend CorpStarter** ! C'est une application React moderne construite avec Vite, dotée de Tailwind CSS pour les styles, React Query pour la récupération de données API, et une interface utilisateur réactive.

## 🛠️ Stack technologique

- **React 18** - Framework de composants UI
- **Vite** - Outil de construction rapide
- **Tailwind CSS** - Framework CSS utilitaire
- **React Query (@tanstack/react-query)** - Gestion de l'état serveur
- **PostCSS + Autoprefixer** - Traitement CSS

## 📋 Prérequis

Vous n'avez besoin d'installer quoi que ce soit ! Docker gère toutes les dépendances. Assurez-vous simplement d'avoir :
- **Docker** (ou OrbStack sur Mac M1/M2/M3)
- **Git**

## 🚀 Démarrage rapide

### 1. Clonez le dépôt
```bash
git clone https://github.com/CorpStarter/CorpStarter.git
cd CorpStarter
```

### 2. Démarrez avec Docker
```bash
docker compose up -d --build
```

### 3. Accédez au Frontend
Le frontend sera disponible à : **http://localhost:5173**

## 📁 Structure du projet

```
frontend/
├── src/
│   ├── App.jsx              # Composant React principal
│   ├── main.jsx             # Point d'entrée
│   ├── index.css            # Styles globaux
│   ├── App.css              # Styles du composant App
│   ├── api/                 # Couche de service API
│   ├── components/          # Composants réutilisables
│   ├── pages/               # Composants de pages
│   ├── context/             # Fournisseurs de contexte
│   └── assets/              # Images, polices, etc
├── public/                  # Ressources statiques
├── package.json             # Dépendances
├── vite.config.js           # Configuration Vite
├── tailwind.config.js       # Configuration Tailwind
├── postcss.config.js        # Configuration PostCSS
└── eslint.config.js         # Configuration ESLint
```

## 📦 Scripts npm disponibles

À l'intérieur du conteneur Docker :

```bash
# Serveur de développement avec HMR
npm run dev

# Construire pour la production
npm run build

# Aperçu de la construction de production
npm run preview

# Linter le code
npm run lint
```

## 🔗 Intégration API

Le frontend se connecte à l'API du backend à :
- **Développement** : `http://localhost:8000/api`
- **Production** : Mettez à jour dans `.env.local`

### Exemples d'appels API

```javascript
// Utilisant React Query
import { useQuery } from '@tanstack/react-query';

const { data: users } = useQuery({
  queryKey: ['users'],
  queryFn: () => fetch('http://localhost:8000/api/users').then(r => r.json())
});
```

## 🎨 Tailwind CSS

Le framework CSS utilitaire est déjà configuré. Utilisez simplement les noms de classe :

```jsx
<div className="flex items-center justify-center min-h-screen bg-gradient-to-r from-purple-400 to-pink-600">
  <h1 className="text-4xl font-bold text-white">Bienvenue dans CorpStarter</h1>
</div>
```

## 🐛 Dépannage

### Les modules npm sont introuvables ?
À l'intérieur du conteneur Docker, réinstallez :
```bash
npm install
```

### Le port 5173 est déjà utiliser ?
Modifiez dans `vite.config.js`:
```javascript
export default {
  server: {
    port: 5174
  }
}
```

### Hot Module Replacement ne fonctionne pas ?
Assurez-vous que les volumes Docker sont correctement configurés dans `docker-compose.yml`.

## 📚 Ressources d'apprentissage

- [Documentation React](https://react.dev)
- [Documentation Vite](https://vitejs.dev)
- [Documentation Tailwind CSS](https://tailwindcss.com)
- [Documentation React Query](https://tanstack.com/query/latest)

## 🤝 Contribution

Voir [CONTRIBUTING.md](../CONTRIBUTING.md) pour les directives.

## 📄 Licence

Ce projet fait partie de **CorpStarter**. Voir le fichier LICENSE pour plus de détails.
# React + Vite

This template provides a minimal setup to get React working in Vite with HMR and some ESLint rules.

Currently, two official plugins are available:

- [@vitejs/plugin-react](https://github.com/vitejs/vite-plugin-react/blob/main/packages/plugin-react) uses [Babel](https://babeljs.io/) (or [oxc](https://oxc.rs) when used in [rolldown-vite](https://vite.dev/guide/rolldown)) for Fast Refresh
- [@vitejs/plugin-react-swc](https://github.com/vitejs/vite-plugin-react/blob/main/packages/plugin-react-swc) uses [SWC](https://swc.rs/) for Fast Refresh

## React Compiler

The React Compiler is not enabled on this template because of its impact on dev & build performances. To add it, see [this documentation](https://react.dev/learn/react-compiler/installation).

## Expanding the ESLint configuration

If you are developing a production application, we recommend using TypeScript with type-aware lint rules enabled. Check out the [TS template](https://github.com/vitejs/vite/tree/main/packages/create-vite/template-react-ts) for information on how to integrate TypeScript and [`typescript-eslint`](https://typescript-eslint.io) in your project.
