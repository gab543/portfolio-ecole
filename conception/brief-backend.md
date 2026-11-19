# Brief back-end — Portfolio de Gabriel Caboche

## Objectif

Fournir au site public les contenus du portfolio et permettre à Gabriel de les gérer depuis un espace d’administration sécurisé. Le back-end enregistre les contenus et les messages de contact en base de données.

Ce brief définit le serveur, l’API, la base de données et les fonctions de gestion. Le brief de l’interface d’administration précisera séparément les écrans et leur expérience utilisateur.

## Périmètre fonctionnel

- Lire les contenus publics du profil, du hero, des liens sociaux, des projets, des catégories et des compétences.
- Permettre à l’administrateur connecté de créer, consulter, modifier et supprimer ces contenus.
- Recevoir les messages envoyés par le formulaire public et permettre à l’administrateur de les consulter et de les gérer.
- Gérer les médias associés au profil et aux projets; conserver en base les métadonnées et la référence vers le fichier stocké.
- Ne proposer aucune création de compte publique. L’accès à l’administration est réservé à Gabriel.

## Données et schémas

Les schémas fournis dans [`schemas/`](schemas/) sont le point de départ. Ils représentent notamment les administrateurs, le profil, les réseaux sociaux, les projets, les catégories, les compétences et les médias, ainsi que les associations entre projets et compétences.

Toutes les informations éditoriales visibles sur le site doivent pouvoir être modifiées depuis l’administration. Cela comprend explicitement le titre et l’accroche du hero, la disponibilité, les libellés et liens des actions, le portrait, le CV, les informations de profil, les liens sociaux, les compétences et l’ensemble des champs et médias des projets. Les composants du front-end ne doivent pas contenir de copies figées de ces informations.

Le modèle doit rester relationnel et cohérent avec les livrables MCD, MLD et MPD de l’école. Le système de base de données choisi est PostgreSQL. Avant de figer le modèle, vérifier que les schémas contiennent bien les champs administrables nécessaires, en particulier pour le hero et les médias. Le formulaire de contact doit avoir une entité/table dédiée aux messages; cette entité et ses liens ne sont pas clairement visibles dans les schémas reçus et sont à ajouter ou confirmer. Prévoir au minimum le nom, l’e-mail, le message et la date de réception. Le statut de lecture/traitement reste à décider.

## API proposée

Utiliser une API REST JSON, à confirmer avec le choix du serveur. Les routes publiques sont en lecture seule, à l’exception de la création d’un message de contact. Toute écriture de contenu exige une session administrateur valide.

Routes publiques indicatives :

- `GET /api/profile` — profil, hero, disponibilité, CV et liens sociaux.
- `GET /api/projects` — projets publiés, avec option de mise en avant.
- `GET /api/projects/:slug` — détail d’un projet publié.
- `GET /api/skills` — compétences publiées.
- `POST /api/messages` — envoyer un message de contact.

Routes d’administration indicatives, protégées par authentification :

- `POST /api/admin/session` et `DELETE /api/admin/session` — ouvrir et fermer la session.
- Routes CRUD pour le profil, les liens sociaux, les projets, catégories, compétences et médias.
- Routes pour lister, consulter, marquer comme traité et supprimer les messages reçus.

Ces routes sont une première proposition de contrat; elles seront ajustées au modèle de données et au front-end.

## Sécurité et validation

- Vérifier l’authentification et les droits côté serveur sur chaque route d’administration; masquer une page dans le front-end ne suffit pas.
- Ne jamais enregistrer un mot de passe en clair. Protéger les sessions et les cookies avec des paramètres adaptés à la production.
- Valider et normaliser les données reçues côté serveur, notamment les champs du formulaire, les URL et les fichiers téléversés.
- Limiter les tentatives de connexion et les envois abusifs du formulaire; ne jamais exposer les secrets dans le code ou les réponses API.
- Utiliser HTTPS en production et renvoyer des erreurs contrôlées sans détails internes sensibles.
- S’appuyer sur les recommandations officielles [Express Security Best Practices](https://expressjs.com/en/advanced/best-practice-security/) si Express est retenu, et sur les guides OWASP concernant [l’authentification](https://cheatsheetseries.owasp.org/cheatsheets/Authentication_Cheat_Sheet.html), [les sessions](https://cheatsheetseries.owasp.org/cheatsheets/Session_Management_Cheat_Sheet.html) et [le stockage des mots de passe](https://cheatsheetseries.owasp.org/cheatsheets/Password_Storage_Cheat_Sheet.html).

## Première livraison : initialiser la stack, puis commencer le front

Initialiser le squelette complet choisi : front React/Vite/TypeScript, serveur Node.js/TypeScript/Express, accès de configuration à PostgreSQL hébergé par Supabase, migrations et configuration Vercel. Ajouter un endpoint de santé minimal pour vérifier que l'API démarre. Ne pas inclure de secrets dans le dépôt; fournir un fichier d'exemple pour les variables d'environnement. Une fois cette base en place, passer directement au front et à la première ébauche du hero mobile first. À chaque livraison ultérieure, appliquer la vérification complète décrite dans le [brief portfolio](brief-portfolio.md#vérification-obligatoire-à-chaque-rendu) et utiliser le skill `portfolio-verification` : cohérence avec la stack et les exigences, absence de doublons ou d'outils inutiles, vérification du rendu ou du flux concerné et exécution des contrôles pertinents.

Cette première livraison ne comprend pas encore l'authentification admin, les opérations CRUD, le traitement des messages, le téléversement des médias ni les écrans d'administration. Les détails de stockage des fichiers, de session, de statut des messages, de notification et de contrat complet de l'API seront précisés au moment où ces fonctions seront abordées; ils ne bloquent pas le démarrage du front.

## Déploiement : Vercel

Vercel est la plateforme de déploiement choisie pour le site et l'API Express. Vercel prend en charge Express, mais déploie l’application Express comme une seule Vercel Function plutôt que comme un serveur Node.js persistant. Concevoir l’application pour ce mode d’exécution : ne pas dépendre d’un état conservé en mémoire ni d’un système de fichiers local persistant. Centraliser les secrets dans les variables d’environnement et gérer les connexions PostgreSQL avec du pooling adapté aux fonctions. Voir la [documentation Express sur Vercel](https://vercel.com/docs/frameworks/backend/express) et les [recommandations de pooling pour Vercel Functions](https://vercel.com/kb/guide/connection-pooling-with-functions).

Supabase est choisi pour héberger PostgreSQL. L'API Express déployée sur Vercel se connectera à cette base avec des variables d'environnement côté serveur. Dans l'architecture retenue ici, Supabase sert d'hébergeur PostgreSQL; son API Data, son authentification et son stockage de fichiers ne sont pas inclus dans le choix actuel. Le service historiquement nommé Vercel Postgres n’est plus disponible pour les nouveaux projets. Voir la [documentation PostgreSQL de Vercel](https://vercel.com/docs/postgres) et l'[intégration Supabase du Marketplace](https://vercel.com/marketplace/supabase/supabase).

Les fichiers ne doivent pas être écrits sur le disque de la fonction pour y rester. Vercel Blob est une option pour les images et le CV; si les uploads serveur dépassent 4,5 Mo, prévoir un upload direct depuis le navigateur ou un autre flux adapté. Voir la [documentation Vercel Blob](https://vercel.com/docs/vercel-blob).

## Choix reportés, non bloquants pour commencer

- Stockage des images et du CV; Vercel Blob reste une option. Si Vercel Blob est retenu, tenir compte de la limite de 4,5 Mo pour les uploads serveur et préférer un upload direct navigateur pour les fichiers plus volumineux.
- Durée des sessions et procédure de récupération d’accès administrateur.
- Statuts et durée de conservation des messages; éventuelle notification par e-mail.
- Contrat final des routes, format des erreurs et champs précis du modèle après vérification des schémas.
- Région de déploiement du serveur et de la base, à rapprocher lorsque possible.
