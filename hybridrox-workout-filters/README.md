# HybridRox Workout Filters Plugin

## Assumptions
1. WordPress 6+ and Elementor are installed and active.
2. Node 18+ is available for asset build.
3. Elementor Pro is optional; widget works with free Elementor.

## Features
- `workout` CPT + taxonomies: `workout_type`, `equipment`, `modality`, `goal`, `difficulty`, `location`, `hyrox_station`
- REST API:
  - `GET /wp-json/hybridrox/v1/workouts`
  - `GET /wp-json/hybridrox/v1/workouts/facets`
- Reusable React+TypeScript filtering UI with URL sync/localStorage
- Shortcode embed + native Elementor widget
- Astra/Elementor safe scoped styles (`.hybridrox-filters-app`)
- WP-CLI seeder to generate 50+ workouts

## Install
1. Copy `hybridrox-workout-filters` to `wp-content/plugins/`.
2. Build assets:
   ```bash
   cd wp-content/plugins/hybridrox-workout-filters
   npm install
   npm run build
   ```
3. Activate plugin in WP Admin.

## Shortcode
```text
[hybridrox_workouts preset="hyrox_workouts" per_page="12" default_sort="newest" instance="hyrox-1"]
```

Optional shortcode attributes:
- `preset`: `hyrox_workouts` or `strength_sessions`
- `instance`: unique key for URL/localStorage namespace
- `per_page`
- `default_sort`
- `default_filters` JSON
- `show_filters` comma-separated keys
- `custom_schema` JSON

## Elementor Widget
1. Open Elementor editor.
2. Add **HybridRox Workout Filters** widget.
3. Configure:
   - Preset
   - Instance key
   - Per page
   - Default sort
   - Show filters
   - Default filters JSON
   - Custom schema JSON

Widget uses the same renderer as shortcode and works in editor preview mode.

## Presets and Schema
Presets are in `includes/class-hybridrox-workout-filters-presets.php`.

Schema supports:
- `multi-select`
- `single-select`
- `range`
- `boolean`
- `date-range`
- `tags`
- `search`

To add a preset, append to `Presets::all()`.

## REST usage
Example:
```bash
curl "https://example.com/wp-json/hybridrox/v1/workouts?search=sled&sort=duration_asc&page=1&per_page=12&equipment=sled&duration_min=20&duration_max=45"
```

Response shape:
```json
{
  "items": [],
  "total": 100,
  "total_pages": 9,
  "facets": {}
}
```

## URL params
State is persisted as query params namespaced by instance key.
Example for instance `hyrox-1`:
- `?hyrox-1_search=rower`
- `?hyrox-1_sort=popularity`

## Seeder
Generate demo data:
```bash
wp hybridrox seed-workouts --count=60
```

## Troubleshooting Elementor + Astra
- Ensure plugin assets are built (`assets/index.js`, `assets/index.css`).
- Clear Elementor cache + regenerate CSS.
- If editor preview is stale, refresh after save.
- Keep custom theme overrides away from `.hybridrox-filters-app` scope.

## Testing plan
- JS unit tests: `npm test`
- Manual PHP checks:
  1. Activate plugin and confirm CPT/taxonomies in admin.
  2. Hit REST endpoints and verify filter query mapping.
  3. Place shortcode on Astra page and Elementor page.
  4. Verify URL syncing and pagination behavior.
