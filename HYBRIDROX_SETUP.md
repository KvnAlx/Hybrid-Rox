# Guide MVP Hybridrox (FR)

## 1) Installation
1. Copier le dossier `hybridrox-mvp` dans `wp-content/plugins/`.
2. Activer **Hybridrox MVP System** dans WordPress.
3. Vérifier que ces plugins sont actifs : Elementor, ACF, FacetWP.

## 2) Créer un nouvel entraînement
1. Aller dans **Entraînements → Ajouter**.
2. Remplir le titre, le contenu principal et l'image mise en avant.
3. Utiliser la box **Catégorisation de l'entraînement** : chaque taxonomie est en **liste déroulante multi-sélection**.
4. Compléter les champs ACF dans **Données de l'entraînement**.
5. Publier.

> Alerte : la combinaison **Sans équipement** + machine (**Rameur / Ski Erg**) déclenche un avertissement admin.

## 3) Page listing /workouts/
- Créer une page `workouts`.
- Dans les attributs de page, sélectionner le template **Hybridrox - Liste des entraînements**.
- Ou bien utiliser les shortcodes dans Elementor :
  - `[hybridrox_workout_filters]`
  - `[hybridrox_workout_grid]`

## 4) Filtres FacetWP
Facettes créées automatiquement : durée, niveau, objectifs, format, équipement, stations, environnement, intensité.

## 5) Tri
Ordre par défaut :
1. entraînements mis en avant
2. plus récents

## 6) Données d'exemple
5 entraînements réalistes sont créés à l'activation (une seule fois).

## 7) Extension taxonomies
Vous pouvez ajouter des termes via WordPress admin ou dans `Hybridrox_CPT_Tax::ensure_default_terms()`.
