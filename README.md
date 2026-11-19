# Portfolio de Gabriel Caboche

Squelette initial du portfolio, avec un front React/Vite/TypeScript, une API Express/TypeScript et une migration PostgreSQL de départ.

## Démarrage local

```bash
npm install
npm run dev
```

- Front : http://localhost:5173
- API : http://localhost:4000/api/health

Le serveur API démarre sans base configurée. Pour utiliser PostgreSQL, renseigner `DATABASE_URL` à partir de `.env.example` avec l'URL de connexion Supabase. Ne pas ajouter de secret au dépôt. Pour les fonctions Vercel, utiliser l'URL du pooler transactionnel fournie par Supabase.

La migration de départ se trouve dans `database/migrations/`. Son modèle est provisoire et ne couvre pas encore les messages, les médias, les projets ni l'administration.

## Scripts

- `npm run dev` : front et API en parallèle.
- `npm run dev:web` : front seul.
- `npm run dev:api` : API seule.
- `npm run build` : build front dans `public/`, répertoire servi comme ressource statique par Vercel.
- `npm run typecheck` : vérification TypeScript (non lancée pendant cette initialisation).

Les routes métier backend sont laissées en TODO. Les autres sections publiques sont des repères à compléter.
