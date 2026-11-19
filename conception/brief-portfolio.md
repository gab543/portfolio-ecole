# Brief de conception — Portfolio de Gabriel Caboche

## Objectif

Créer un portfolio professionnel pour présenter le profil, les projets, les expériences et les compétences de Gabriel Caboche, et faciliter ses recherches de stage puis d'alternance.

## Profil

- Étudiant en deuxième année de développement full stack.
- Projet de poursuite d'études : master en cybersécurité.
- Localisation : Paris / Île-de-France.
- Recherche prioritaire : stage en cybersécurité, avec possibilité de poursuivre en alternance.
- Période de stage : avril à juillet 2027 (4 mois).
- Ouverture à un stage full stack si une opportunité en cybersécurité ne se présente pas.

## Direction visuelle

- Style minimaliste, soigné et professionnel.
- Palette souhaitée : noir dominant en fond, beige crème pour la typographie principale et rouge franc pour les accents.
- Typographie du hero : grand titre sans-serif très gras; nom de Gabriel en signature manuscrite rouge au premier plan.
- Référence de composition pour le hero : grande typographie beige en arrière-plan, portrait détouré ou cadré qui la chevauche, nom/signature rouge au premier plan, navigation fine en haut et introduction avec contact en bas.
- Garder le rouge comme accent maîtrisé pour préserver le confort de lecture et éviter une esthétique agressive ou trop "cyber".
- Animations au scroll qui font évoluer la composition et le mouvement de la page, au-delà de simples apparitions d'éléments.
- Les animations doivent soutenir la lecture et la découverte des projets.
- Prévoir une expérience réduite lorsque l'utilisateur active `prefers-reduced-motion`.
- Interface responsive et lisible sur mobile comme sur ordinateur.

## Prompt front-end — première version

Conçois le front-end d'un portfolio professionnel pour Gabriel Caboche, étudiant en deuxième année de développement full stack à Paris / Île-de-France. Il prépare une poursuite d'études en master cybersécurité et recherche un stage de quatre mois, d'avril à juillet 2027, prioritairement en cybersécurité. Il est aussi ouvert à une opportunité full stack et souhaite, si possible, continuer ensuite en alternance.

L'interface doit être minimaliste, distinctive et crédible pour une candidature en cybersécurité comme en développement web. Utilise une hiérarchie typographique forte, une grille claire, beaucoup d'espace maîtrisé et des détails visuels précis. Évite le template portfolio générique, les effets décoratifs sans fonction et les textes fictifs. Les contenus des projets, expériences et compétences seront fournis plus tard : prévois une structure élégante qui fonctionne avec ces contenus sans en inventer.

### Référence de direction artistique

Prends l'image de référence fournie comme inspiration de composition et de contraste, sans recopier son identité, ses textes ni ses éléments de marque. Utilise un fond noir profond comme surface dominante, une typographie beige à fort contraste et un rouge sombre comme accent ponctuel. Dans le hero, compose un grand titre typographique derrière le sujet, un portrait central qui chevauche le titre, puis le nom de Gabriel en signature rouge au premier plan. Ajoute une barre de navigation fine en haut et place l'accroche courte et les moyens de contact dans la partie basse du premier écran. L'ensemble doit rester lisible : aucun mot important ne doit être masqué par le portrait ou coupé aux bords.

L'image de référence représente Gabriel, mais elle sert d'abord de référence visuelle et n'est pas confirmée comme portrait final. Prévoir deux variantes du hero : avec un portrait central facilement remplaçable par une autre photo qu'il choisira, et sans portrait où le titre devient l'élément principal. Ne pas générer de faux portrait ni présenter une personne fictive comme Gabriel. Ne pas reprendre la phrase de présentation visible dans l'image de référence. Le titre, l'accroche, les informations de disponibilité et les médias du hero font partie des contenus administrables. Pour la première ébauche, utiliser des données provisoires faciles à remplacer; la formulation définitive pourra être renseignée plus tard depuis l'administration.

La cible finale est un front-end public responsive avec React et Vite, conçu en mobile first : commencer par l'expérience mobile puis l'enrichir pour les écrans plus larges. Elle comprend les routes publiques côté React : accueil long et animé, Profil, Contact, Tous les projets et une page de détail par projet. La navigation principale donne accès à l'accueil, au profil, à la collection complète des projets et au contact. Depuis l'accueil, la sélection de projets mène à la liste complète puis aux études de cas détaillées. Le périmètre serveur, les données, l'API et les fonctions d'administration sont décrits dans le [brief back-end](brief-backend.md). Le design des écrans d'administration sera détaillé dans un brief séparé; le présent brief décrit uniquement les pages publiques.

Le code doit rester clair et maintenable. Appliquer le principe DRY en évitant les duplications inutiles et en factorisant les éléments réellement réutilisés, sans créer d'abstractions superflues.

### Vérification obligatoire à chaque rendu

Après chaque itération de code ou d'interface, avant de présenter le rendu, effectuer une vérification complète avec le skill local [`portfolio-verification`](../.agents/skills/portfolio-verification/SKILL.md). Cette étape est obligatoire même pour une petite modification; adapter les contrôles aux fichiers touchés et signaler honnêtement ceux qui n'ont pas pu être faits.

La revue doit au minimum confirmer que :

- le rendu respecte la stack retenue, les briefs, les décisions validées et le périmètre de l'étape;
- les nouvelles fonctions, composants, données, styles, appels API et dépendances ne dupliquent pas l'existant et n'ajoutent rien d'inutile ou de prématuré;
- le comportement fonctionne et, pour une interface, que le rendu réel a été inspecté sur mobile puis sur un écran plus large, avec les interactions concernées;
- les points applicables d'accessibilité, de sémantique, de lisibilité, de responsive et de réduction des animations ont été contrôlés;
- les contrôles déjà disponibles et pertinents dans le projet (build, TypeScript, lint, tests existants ou vérification du flux concerné) ont été exécutés et leurs résultats examinés.

Corriger les défauts trouvés dans le périmètre puis refaire les contrôles affectés. Ne pas déclarer une vérification réussie sans l'avoir exécutée; si l'inspection visuelle ou un contrôle n'est pas possible, le dire explicitement et préciser la limite dans le bilan du rendu. Les skills spécialisés de conception, accessibilité, React ou animation complètent cette revue lorsque le changement le justifie.

### Contenus et administration

À terme, toutes les informations et tous les contenus éditoriaux visibles sur les pages publiques doivent être gérés depuis le tableau d'administration, enregistrés durablement et transmis au site public par l'API. Cela comprend notamment les textes du hero (titre, accroche, disponibilité et libellés des actions), les informations et médias du profil, les projets et leurs contenus associés, les compétences, les coordonnées, les liens sociaux et le CV. Ne pas figer ces contenus dans les composants de page. Les règles de gestion et le contrat back-end sont précisés dans le [brief back-end](brief-backend.md); le présent brief fixe le besoin côté site public.

S'appuyer sur les schémas présents dans `conception/schemas/` et les vérifier avant de figer le modèle. Si un contenu public nécessaire, tel que l'accroche ou le portrait du hero, n'a pas de champ correspondant, le signaler et prévoir de compléter le schéma.

### Première étape de réalisation

Au premier lancement de ce brief dans un outil de génération ou de développement, initialiser le squelette de toute la stack choisie : front React/Vite/TypeScript, API Node.js/TypeScript/Express, connexion à PostgreSQL hébergé par Supabase et configuration de déploiement Vercel. Préparer les fichiers de configuration, les variables d'environnement d'exemple et l'organisation du code sans secrets. Puis commencer directement le front, en réalisant une première ébauche mobile first du hero. Le hero peut rester simple et provisoire : il n'a pas besoin d'être finalisé. Ranger ses textes et valeurs provisoires dans une source de données centralisée, séparée des composants, afin de faciliter leur remplacement ultérieur par les données de l'API et leur édition depuis l'administration. Garder le code clair et appliquer DRY lorsque des éléments se répètent. Laisser les autres sections sous forme de repères ou de TODO, sans construire les pages Profil, Contact, Tous les projets et les fiches projet. À ce stade, ne pas construire les fonctions métier du back-end, l'authentification admin, le CRUD des contenus, le traitement des messages, le stockage des médias ni l'interface d'administration. Ces éléments seront implémentés ensuite, quand le front sera avancé.

La page d'accueil doit suivre cet ordre :

1. **Navigation** — barre fine et discrète, logo typographique ou nom, ancres vers Profil, Projets et Contact, lien distinct vers « Tous les projets », et accès au CV.
2. **Hero** — grand titre typographique et portrait selon la direction artistique choisie; afficher clairement « Gabriel Caboche », étudiant en développement full stack orienté vers la cybersécurité, basé à Paris / Île-de-France. Indiquer sa recherche de stage de quatre mois, avril–juillet 2027, en cybersécurité en priorité, ouvert au full stack et à une poursuite en alternance. Ajouter deux actions visibles : « Découvrir mes projets » et « Me contacter »; le CV peut être un lien secondaire.
3. **Présentation courte** — quelques lignes personnelles sur son parcours, son intérêt pour la cybersécurité et le développement. N'utiliser que des informations validées par Gabriel; garder le texte éditable jusqu'à ce qu'il fournisse sa formulation.
4. **Projets sélectionnés** — mettre en avant deux ou trois projets réels, avec visuel, titre, résumé très court, rôle et technologies disponibles. Chaque carte ouvre sa page détaillée. Ajouter un appel à l'action « Voir tous les projets ».
5. **Aperçu des compétences** — quelques familles de compétences techniques et transversales sous forme de liste claire, sans jauges de niveau arbitraires; lien vers les détails du profil ou des projets si nécessaire. Ne rien inventer avant validation.
6. **Parcours / expériences** — aperçu chronologique seulement si Gabriel fournit des expériences, formations ou étapes pertinentes. Cette section peut rester compacte ou être omise si elle répète le profil.
7. **Contact final** — rappel de disponibilité pour le stage, adresse e-mail et liens professionnels fournis par Gabriel, avec un formulaire accessible comprenant les champs nom, e-mail et message. Enregistrer les messages en base pour pouvoir les gérer depuis l'administration. Prévoir validation, confirmation de succès et retour en cas d'erreur.

L'accueil doit rester une synthèse : la page Profil contient les détails du parcours; les descriptions complètes des projets appartiennent à leurs pages dédiées; le CV contient le détail du parcours; la page « Tous les projets » contient l'inventaire complet; la page Contact porte le formulaire et les moyens de contact validés. Ne pas afficher de témoignages, de chiffres de performance ou de références non confirmés.

La page « Tous les projets » doit présenter la collection complète sous forme de grille ou de liste éditoriale responsive, avec accès direct aux fiches. Prévoir un filtrage par catégorie ou technologie uniquement si la quantité réelle de projets le justifie; ne pas ajouter des filtres décoratifs sans utilité.

Chaque page de projet doit proposer un titre, un résumé, le contexte ou le besoin, le rôle de Gabriel, les étapes de réalisation, les choix techniques, les résultats ou apprentissages, les compétences associées, les médias fournis et les liens disponibles. Ces champs doivent rester facultatifs lorsque l'information n'existe pas; ne rien inventer. Ajouter un retour clair à la liste des projets et une navigation vers le projet précédent ou suivant si cela améliore le parcours.

### Direction du mouvement — transition latérale du hero

Le hero conserve sa composition de base et reste le premier écran du portfolio. La nouvelle intention est de faire évoluer la navigation vers un parcours latéral commandé par le scroll : le titre du hero se resserre vers un côté, traverse un passage visuellement trop étroit, puis ressort de l'autre côté avec un rebond. L'image recherchée est celle d'une balle rebondissante que l'on essaie de faire passer dans un trou trop petit. Il s'agit d'une métaphore de mouvement et de tension, pas d'une demande d'ajouter nécessairement un dessin littéral de balle ou de trou.

#### Déroulé attendu

1. **État initial** — à l'arrivée, le hero garde son apparence de base : titre complet, nom, navigation, accroche, disponibilité et actions restent visibles et lisibles. L'animation ne doit pas modifier le contenu ni retarder la compréhension du profil.
2. **Compression** — lorsque Gabriel commence à faire défiler la page, le titre devient le foyer du mouvement. Il se resserre progressivement vers un côté du hero, comme si une force le poussait vers un point de passage trop étroit. La tension doit monter avant le franchissement. La compression est un effet visuel temporaire : le texte ne doit pas devenir durablement illisible, être coupé au bord de l'écran ou perdre des mots.
3. **Franchissement** — après cette montée en tension, le titre passe rapidement et nettement de l'autre côté. Le contraste entre la compression retenue et ce passage brusque donne l'impulsion recherchée. Le changement de côté doit être facile à suivre à l'œil et ne pas ressembler à une disparition suivie d'une réapparition sans lien.
4. **Rebond et stabilisation** — à la sortie du passage, le mouvement dépasse légèrement sa position d'arrivée, revient et se stabilise de l'autre côté. Le rebond doit évoquer une balle, rester bref et contrôlé, puis laisser le titre dans une position nette qui permet de poursuivre la lecture. Éviter les oscillations répétées ou décoratives.
5. **Suite du parcours** — cette transition introduit le déplacement latéral du portfolio. Les sections suivantes doivent donner l'impression de poursuivre le parcours sur le côté, dans un ordre de lecture cohérent. Le hero reste le point de départ; la transition ne doit pas faire croire qu'on a quitté le site ou perdu le fil de navigation.

#### Relation avec le scroll

Le geste de défilement pilote directement l'avancement de la séquence, plutôt que de déclencher une animation autonome à durée fixe. Un scroll lent doit permettre de percevoir les étapes de compression, de franchissement et de rebond. En faisant défiler dans l'autre sens, l'utilisateur doit pouvoir revenir progressivement vers l'état initial sans saut, redémarrage incohérent ni blocage. La partie « d'un coup » désigne un franchissement visuellement rapide dans la progression, pas une animation qui se déclenche sans contrôle ou qui saute arbitrairement plusieurs écrans.

Garder le scroll sous le contrôle de l'utilisateur : ne pas enfermer le visiteur dans une séquence, neutraliser sa molette ou rendre le retour en arrière difficile. Le mouvement peut transformer une progression verticale en déplacement latéral sur grand écran, mais le geste doit rester prévisible et la navigation utilisable au clavier, à la molette, au pavé tactile et au toucher. Le mécanisme exact de défilement horizontal et la façon d'enchaîner les sections seront décidés au moment du prototype; ne pas ajouter de défilement horizontal forcé avant d'avoir vérifié son confort.

#### Composition, lisibilité et adaptations

- Le titre porte l'essentiel du mouvement. Garder les éléments qui aident à s'orienter — nom, navigation et actions — suffisamment stables pour que l'utilisateur comprenne toujours où il se trouve et puisse agir.
- Si le portrait est présent, il peut servir de repère visuel pendant la traversée; ne pas lui faire concurrencer le mouvement du titre ni masquer le contenu important. La variante sans portrait doit conserver le même principe de mouvement grâce à la composition typographique.
- Préserver le contraste, la hiérarchie et la lisibilité du titre aux états de départ et d'arrivée. Éviter tout chevauchement non intentionnel, débordement, contenu inaccessible ou mise en page qui dépend d'une taille d'écran unique.
- Concevoir d'abord le comportement mobile. Sur petit écran, réduire l'amplitude et la complexité, ou présenter une version simplifiée; ne pas piéger le défilement tactile ni provoquer un débordement horizontal involontaire.
- Avec `prefers-reduced-motion`, supprimer le déplacement latéral, la compression et le rebond. Présenter directement une composition statique et permettre de poursuivre vers la suite du contenu sans étape animée obligatoire.
- Garder le contenu textuel dans son ordre sémantique normal. Les transformations visuelles ne doivent ni dupliquer le texte annoncé aux technologies d'assistance, ni modifier le parcours du focus clavier.

#### Critères de validation du futur rendu

- Le premier écran est reconnaissable comme le hero actuel avant toute interaction.
- Un défilement progressif révèle clairement la compression vers un côté, le franchissement rapide du point étroit, puis le rebond contrôlé de l'autre côté.
- La progression répond au scroll dans les deux sens; le mouvement ne se joue pas tout seul et ne bloque pas la navigation.
- Le titre et les actions restent compréhensibles et utilisables, sans coupure, chevauchement involontaire ni perte d'information.
- Le déplacement latéral s'intègre à l'arrivée des sections suivantes au lieu de sembler être un effet isolé.
- Le rendu a été inspecté sur mobile et sur écran large, avec une variante conforme à `prefers-reduced-motion`.

L'intention de mouvement est validée; les choix de direction exacte, d'amplitude, de durée relative, de force du rebond, de point de passage et d'enchaînement avec les autres sections restent à définir en prototypant. GSAP avec ScrollTrigger reste le choix de départ pour lier la progression au scroll; intégrer GSAP dans React avec ses pratiques de cycle de vie et de nettoyage. Les références [Stefano Bartoletti](https://www.stefanobartoletti.it/), [Dennis Snellenberg](https://dennissnellenberg.com/) et [Jordan Gilroy](https://www.jordangilroy.com/) constituent des références d'animation parmi d'autres, jamais une liste exhaustive. Les skills front-end installés serviront aussi de références de conception et d'implémentation. Ne reprends pas l'identité visuelle, les couleurs, la typographie, le contenu, le ton ni la composition de ces sites. Garder `prefers-reduced-motion` et les adaptations décrites ci-dessus comme critères de réalisation.

### Pistes technologiques front-end

La liste suivante, proposée par Gabriel, est non exhaustive. Ce sont des pistes à évaluer selon l'effet réellement nécessaire, pas des dépendances à installer toutes ensemble. Préférer CSS ou les API natives pour les effets simples. Éviter d'ajouter plusieurs outils qui couvrent le même besoin sans justification claire.

| Technologie | Usage possible | Avis pour ce portfolio |
| --- | --- | --- |
| [GSAP + ScrollTrigger](https://gsap.com/docs/v3/Plugins/ScrollTrigger/) | Timelines et animations dont la progression suit le scroll. | Choix de départ retenu pour le scroll-scrubbing et les séquences plus complexes. |
| [Anime.js](https://animejs.com/documentation/events/onscroll) | Timelines et effets DOM; sa version actuelle propose aussi un contrôle par scroll et une intégration React documentée. | Alternative à GSAP pour certaines animations. Les deux se recouvrent : ne pas les installer ensemble par défaut. Garder GSAP comme base tant qu'un besoin précis ne justifie pas de changer. |
| [Lenis](https://github.com/darkroomengineering/lenis) | Adoucir le défilement et synchroniser le scroll avec des animations ou un canvas. | Optionnel; ne remplace pas ScrollTrigger. Garder le scroll natif au démarrage et ne l'ajouter que si le ressenti le justifie, en vérifiant ancres, clavier, tactile et réduction des mouvements. |
| [Vanta.js](https://www.vantajs.com/) | Arrière-plans animés WebGL à partir d'effets prédéfinis. | Rapide à essayer, mais peut imposer une esthétique générique et coûter cher sur certains appareils. Si utilisé, limiter à une zone, prévoir une image ou couleur de repli mobile et mesurer l'impact. |
| [ShaderGradient](https://github.com/ruucm/shadergradient) | Dégradés WebGL animés, intégrables comme composant React. | Piste adaptée à un fond abstrait si la direction artistique le demande. Ajoute l'écosystème React Three Fiber/Three; ne pas le cumuler avec Vanta ou OGL pour le même arrière-plan. |
| [OGL](https://github.com/oframe/ogl) | Rendu WebGL bas niveau et shaders personnalisés. | Puissant pour créer un effet sur mesure, mais demande davantage de code et de maîtrise WebGL. À réserver à un besoin visuel précis, pas au démarrage du portfolio. |
| [Atropos.js](https://atroposjs.com/docs/react) | Inclinaison 3D/parallaxe déclenchée par le pointeur ou le toucher, notamment sur des cartes. | À tester avec retenue sur un élément où la profondeur aide à lire l'interaction. Éviter les effets répétitifs sur toutes les cartes; simplifier sur mobile. |
| [Particles.js](https://github.com/VincentGarreau/particles.js) | Fond de particules configurable. | À garder comme référence puisqu'il a été proposé. Pour un nouveau projet React/TypeScript, préférer [tsParticles](https://particles.js.org/guides/react), qui fournit un composant React officiel et des types; n'ajouter des particules que si elles servent la composition. |
| [HyperFrames](https://hyperframes.app/docs/1-startup/2-quickstart) | Création et rendu de vidéos/motion graphics à partir de HTML, CSS et JavaScript. | Outil de production de médias, pas une bibliothèque d'animation à intégrer au site public. Éventuellement utile pour fabriquer une vidéo de présentation d'un projet. |

La première étape du portfolio ne doit pas installer ces outils par anticipation. Construire d'abord le socle et l'ébauche du hero; choisir ensuite les dépendances nécessaires à une animation définie. Pour chaque effet, garder une variante légère sur mobile et respecter `prefers-reduced-motion`.

Prévois les états interactifs, le clavier, des contrastes suffisants, des zones de clic confortables, des libellés explicites et une structure HTML sémantique. Garde le contenu séparé des composants afin qu'il puisse ensuite être alimenté par l'API et administré depuis l'espace sécurisé. N'invente ni parcours, ni projet, ni compétence, ni témoignage.

À définir ensemble avant la version finale : nuance exacte de rouge, références visuelles, choix des animations dominantes, pages exactes et outils front-end.

## Contenu à compléter avec Gabriel

- Accroche professionnelle courte.
  - Bio validée : « Le développement m’attire depuis le collège. Au lycée, mes cours de STI2D-SIN ont confirmé cette envie. Aujourd’hui, je m’intéresse à la cybersécurité parce que j’aime me confronter à des problèmes et chercher comment les résoudre. Avec les progrès de l’IA, j’ai le sentiment que la cybersécurité est le dernier domaine de la tech où je pourrai encore trouver ce type de défis. Et puis, tout simplement, ça me plaît. »
- Projets et expériences réels.
  - Projet retenu en premier : Nexa v2, refonte du site de l’agence de création web Nexa, réalisé seul. L’objectif était de donner une meilleure image de l’entreprise et de montrer dès le premier abord ce dont elle est capable, afin de mieux présenter et vendre ses services. Gabriel a repris l’ensemble du front-end. Le dépôt indique React, TypeScript et Vite; Node.js sert à l’environnement de développement. Le principal défi était de démarrer la conception visuelle : trouver les premières idées d’agencement et dépasser le syndrome de la page blanche. Pour y parvenir, Gabriel s’est appuyé sur les échanges avec son collègue et sur des références trouvées sur Awwwards et Dribbble. Le site est en ligne et utilisé par Nexa : https://www.nexa-group.fr/. Partie à mettre en avant dans le portfolio : le hero dans son ensemble, sans détail particulier à isoler. Apprentissage : « Cette refonte m’a fait découvrir l’UI/UX. Au départ, je ne connaissais pas vraiment ce domaine et il ne m’attirait pas particulièrement, mais le projet m’a appris à l’apprécier. »
  - Autres projets à ajouter plus tard : revenir à cette section pour compléter la liste au fur et à mesure.
- Compétences techniques et transversales.
- Liens professionnels, coordonnées et CV téléchargeable.
- Préférences précises de couleurs, typographies et références visuelles.

## Périmètre fonctionnel demandé par l'école

- Pages publiques : profil, CV, projets/expériences, compétences et formulaire de contact.
- Administration sécurisée : gestion du profil, projets, compétences et messages reçus.
- Données persistées en base de données.
- Livrables : diagramme de cas d'utilisation, MCD, MLD, MPD, dépôt Git, README, accès administrateur, site en ligne et export de base de données.

## État des schémas reçus

Les images reçues sont conservées dans `schemas/`. Elles constituent une première ébauche à compléter et à vérifier avant de figer le modèle de données.

## Skills de référence installés

Les skills sélectionnés pour le portfolio sont copiés dans `.agents/skills/` à la racine du projet. Voir [l'index des skills](../.agents/skills/INDEX.md) pour leur rôle et le moment où les utiliser.

- `animate` — concevoir et implémenter une animation ciblée.
- `improve-animations` — auditer l'ensemble des animations et préparer un plan d'amélioration.
- `optimize-web-animations` — mesurer et améliorer les performances des animations web.
- `impeccable` — concevoir et affiner l'interface générale; utile au-delà du seul mouvement.
- `gsap-scrolltrigger` — implémenter les animations dont la progression est liée au scroll.
- `gsap-react` — appliquer les bonnes pratiques GSAP dans React, notamment le nettoyage au démontage.
- `humanizer` — relire les textes du portfolio pour les rendre naturels, en préservant la voix de Gabriel et sans ajouter de faits.
- `portfolio-verification` — vérifier chaque livraison par rapport au brief, à la stack, au rendu réel, aux doublons et aux contrôles pertinents.

Ne pas charger ni appliquer tous les skills spécialisés à chaque tâche. Choisir ceux qui correspondent au travail; lancer `portfolio-verification` après chaque livraison de code ou d'interface. Utiliser `humanizer` pour les textes destinés au portfolio, avec les informations validées par Gabriel.

## Liste pour terminer le prompt

### Déjà décidé

- [x] Profil cible : Gabriel Caboche, étudiant en deuxième année de développement full stack, Paris / Île-de-France.
- [x] Recherche : stage de quatre mois d'avril à juillet 2027, cybersécurité en priorité, full stack en ouverture, alternance possible ensuite.
- [x] Direction : minimaliste, noir dominant, typographie beige crème, accents rouge franc.
- [x] Référence du hero : grand titre, portrait central remplaçable, nom rouge en signature, navigation fine et coordonnées visibles.
- [x] Mouvement : le scroll pilote directement la progression des animations; adapter les effets au mobile et à la réduction des animations.
- [x] Références de mouvement fournies : [Stefano Bartoletti](https://www.stefanobartoletti.it/), [Dennis Snellenberg](https://dennissnellenberg.com/) et [Jordan Gilroy](https://www.jordangilroy.com/). Elles servent uniquement à discuter des animations et interactions.
- [x] Structure publique : accueil synthétique, page Profil, page Contact, page « Tous les projets », puis une page détaillée par projet.
- [x] Accueil : navigation, hero, courte présentation, projets sélectionnés, aperçu des compétences, parcours si pertinent, contact.

### À préciser ensemble

Les points ci-dessous concernent les étapes suivantes et ne bloquent pas l'initialisation de la stack ni le démarrage du front avec le hero.

- [ ] Accroche professionnelle courte.
- [x] Courte bio formulée et validée avec Gabriel.
- [ ] Contenus réels : liste de projets, expériences, compétences et formations à afficher.
- [ ] Ressources de contact : choisir la variante du portrait plus tard; réunir le CV, l'e-mail et les profils professionnels à publier.
- [ ] Identité visuelle : nuances hexadécimales et familles typographiques précises; les rôles et directions sont fixés, les valeurs finales pourront être choisies au moment de la maquette.
- [x] Intention du mouvement : le hero évolue vers un parcours latéral; son titre se comprime d'un côté, franchit rapidement un passage trop étroit, rebondit puis se stabilise de l'autre côté sous le contrôle du scroll.
- [ ] Prototype du mouvement : fixer la direction exacte, l'amplitude, le rebond, le point de passage, l'enchaînement des sections et les adaptations mobile après essais visuels.
- [x] Pages publiques définitives : profil et contact auront chacun une page séparée.
- [ ] Pages projet : choisir les champs réellement utiles et décider si un filtrage est nécessaire selon le nombre de projets.
- [x] Formulaire : nom, e-mail et message; enregistrer les messages en base pour la gestion admin.
- [ ] Formulaire : préciser le texte de confirmation et les états de validation/erreur.
- [x] Périmètre d'administration : réaliser un brief séparé; le présent brief couvre seulement le front-end public.
- [x] Approche d'animation : GSAP + ScrollTrigger pour le scroll-scrubbing, avec intégration React adaptée.
- [x] Front-end : React + Vite, avec routes publiques côté React.
- [x] Base de données back-end : PostgreSQL.
- [x] Hébergement PostgreSQL : Supabase, pour la base uniquement dans l'architecture actuelle.
- [x] Back-end : Node.js + TypeScript + Express.
- [x] Déploiement du site et de l'API : Vercel.
- [ ] Stockage des médias et région d'exécution à préciser.
- [ ] Contrat front/back : préciser comment l'interface récupère les projets, le profil, les compétences et les messages depuis l'API.
- [ ] Contraintes finales du prompt : livrables attendus, limites à respecter et éléments que l'agent doit demander plutôt que supposer.
