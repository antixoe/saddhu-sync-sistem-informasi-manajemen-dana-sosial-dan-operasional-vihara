# TODO: Replace Google Maps with Leaflet/OpenStreetMap

## Plan
1. [x] Update resources/views/members/edit.blade.php - Removed map picker (manual address input preferred)
2. [x] Update resources/views/members/create.blade.php - Remove broken Google Maps fallback script
3. [x] Update resources/views/settings/index.blade.php - Mark Google Maps API Key as optional

## Implementation Details
- Use Leaflet.js with OpenStreetMap tiles (free, no API key required)
- Keep reverse geocoding using Nominatim (OpenStreetMap's free geocoding service)
- Maintain the same user experience as before

## COMPLETED

