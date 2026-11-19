---
name: portfolio-verification
description: Vérifie chaque livraison du portfolio par rapport aux briefs, à la stack, au rendu visuel et au code existant. À utiliser après toute modification de code ou d'interface, même petite, avant de présenter le résultat.
---

# Vérification du portfolio

Après chaque livraison de code ou d'interface du portfolio, effectuer une revue complète avant de présenter le résultat. Adapter les vérifications aux fichiers touchés, mais parcourir chaque catégorie et signaler explicitement ce qui n'était pas vérifiable. Une revue ne doit pas devenir une excuse pour élargir le périmètre.

## Revue

1. **Contexte et demandes** — relire le brief concerné, les consignes locales et les décisions déjà validées. Comparer le rendu aux demandes explicites, aux contenus confirmés et aux éléments laissés provisoires. Vérifier que le rendu ne construit pas prématurément des pages ou fonctions hors périmètre.
2. **Stack et architecture** — contrôler que les changements s'intègrent à React, Vite et TypeScript côté front, Node.js, TypeScript et Express côté API, PostgreSQL hébergé par Supabase pour les données, et au déploiement Vercel. Vérifier les chemins d'import, les scripts, les variables d'environnement, les frontières front/API et l'absence de secrets. Ne pas ajouter de dépendance sans besoin réel et compatible avec la stack.
3. **Adéquation fonctionnelle et visuelle** — vérifier le parcours demandé, les états interactifs et le contenu. Pour le site public, examiner le rendu mobile en premier, puis au moins une largeur d'écran plus grande; vérifier le débordement, la hiérarchie, la lisibilité et le comportement des liens et contrôles. Pour un changement d'interface, ouvrir le rendu dans un navigateur et inspecter visuellement les écrans concernés si l'outillage est disponible. Si ce n'est pas possible, dire clairement que la vérification visuelle n'a pas été faite.
4. **Doublons et inutilité** — chercher les composants, fonctions, règles CSS, états, appels API ou dépendances qui répètent une fonction déjà présente. Repérer le code mort, les données dupliquées, les abstractions prématurées et les bibliothèques qui recouvrent un outil déjà retenu. Factoriser seulement une répétition réelle; supprimer les ajouts inutiles dans le périmètre de la tâche. Conserver une solution simple si une abstraction ne réduit pas la complexité.
5. **Qualité et comportement** — vérifier la sémantique HTML, l'accessibilité clavier, les noms accessibles, les contrastes et `prefers-reduced-motion` lorsque concernés. Contrôler les cas d'erreur et de chargement affectés. Pour une modification d'API ou de données, suivre le flux concerné de la requête à la réponse et vérifier les migrations ou variables nécessaires, sans utiliser de vrais secrets.
6. **Vérifications exécutables** — consulter les scripts et contrôles déjà configurés. Lancer ceux qui correspondent aux changements, par exemple build, vérification TypeScript, lint ou tests existants. Pour le front, démarrer l'application et inspecter le rendu lorsque c'est pertinent. Pour l'API, vérifier les routes ou le flux de données touchés. Ne pas prétendre qu'un contrôle a réussi s'il n'a pas été exécuté; ne pas créer une suite de tests disproportionnée pour satisfaire une checklist.
7. **Bilan avant livraison** — corriger les défauts découverts qui relèvent du périmètre, puis refaire les contrôles affectés. Résumer les contrôles réussis et les limites restantes; distinguer un échec d'un contrôle non exécuté. Si un défaut hors périmètre bloque le rendu, le décrire au lieu de le masquer.

## Compétences complémentaires

Charger uniquement les skills spécialisés nécessaires au rendu : `improve-ui` ou `impeccable` pour une revue de conception, `fixing-accessibility` pour une correction d'accessibilité, `react-best-practices` pour plusieurs composants React, et les skills d'animation pour les animations. Ce skill reste le point de contrôle transversal et ne remplace pas ces revues ciblées.
