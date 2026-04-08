# Hybridrox MVP Setup Guide

## 1) Install and activate
1. Copy the `hybridrox-mvp` folder into `wp-content/plugins/`.
2. Activate **Hybridrox MVP System** in WordPress admin.
3. Ensure these plugins are active:
   - Elementor
   - Advanced Custom Fields (ACF)
   - FacetWP

## 2) Add a new workout
1. Go to **Workouts → Add New**.
2. Enter title, content, and featured image.
3. Assign workout taxonomies in the right sidebar:
   - Level, Goal, Format, Equipment, Stations, Environment, Intensity.
4. Fill ACF **Workout Data** tabs:
   - General: short description, featured, duration
   - Structure: warmup, workout blocks, cooldown
   - Conditions: run max distance
   - Coaching: scaling + tips
5. Publish.

> Validation warning: if you select **No Equipment** together with **Rower** or **Ski Erg Machine**, admin shows a warning notice.

## 3) Listing page (/workouts/)
Two options:

### Option A — page template
1. Create page called **Workouts** with slug `workouts`.
2. In Page Attributes, set template to **Hybridrox Workouts Listing**.
3. Publish.

### Option B — Elementor page
Use shortcodes in an Elementor shortcode widget:
- `[hybridrox_workout_filters]`
- `[hybridrox_workout_grid]`

## 4) FacetWP filters
The plugin registers these facets automatically when FacetWP runs:
- duration (number range)
- level
- goals
- format
- equipment
- stations
- environment
- intensity

If needed, edit labels / display options in **FacetWP → Facets**.

## 5) Sorting behavior
Default order for workout archives and FacetWP queries:
1. Featured workouts first (`featured = true`)
2. Newest first

## 6) Seed data
On plugin activation, 5 example workouts are inserted once.
The seed is guarded by option `hybridrox_seeded_workouts`.

## 7) Extend taxonomies
To add more taxonomy values:
1. Go to **Workouts → [taxonomy name]** and add terms manually.
2. Or edit `Hybridrox_CPT_Tax::ensure_default_terms()` in:
   - `hybridrox-mvp/includes/class-hybridrox-cpt-tax.php`

Taxonomies are non-hierarchical and compatible with FacetWP taxonomy facets.
