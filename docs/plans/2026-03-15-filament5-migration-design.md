# Filament 5 Migration + Livewire Storefront (Design)

Date: 2026-03-15

## Summary
Migrate the existing Filament v3-style admin panel to Filament v5 and align the Livewire storefront so the core workflows (staff login, create product, view product on storefront) work end-to-end. Keep existing domain logic, data models, and routes, while updating resource, page, widget, relation manager, and Blade component APIs to Filament 5.

## Goals
- Admin panel works on Filament 5 with all resources, pages, relation managers, widgets, and custom admin Blade components updated.
- Staff login works with the existing staff guard and Filament panel auth.
- Product creation/editing works and product appears in the storefront.
- Storefront uses Livewire only and presents a consistent, normal design with Tailwind v4.

## Non-goals
- Rewriting the data model or domain logic.
- Migrating to a new Laravel 12 streamlined structure (keep current Laravel 10-style structure).
- Replacing Livewire with another frontend framework.

## Scope
- Admin panel: `app/Filament/**`, admin views under `resources/views/admin/**`, and related support components in `app/Support/**`.
- Storefront: `app/Livewire/**` and `resources/views/livewire/**`.
- Tests: add/adjust focused feature tests to validate admin and storefront flows.

## Approach (Phased)
1. Inventory and baseline: list all Filament resources, pages, widgets, relation managers, and admin Blade components; identify v3 APIs to change.
2. Core panel upgrade: update panel registration, middleware, and configuration to Filament 5 conventions.
3. Resource migration batch: update resources, pages, relation managers, forms, tables, and infolists to v5 APIs.
4. Blade component migration: update custom admin Blade component usage to v5 components and slots.
5. Storefront alignment: keep Livewire components, update any deprecated patterns, ensure product data and media render correctly.
6. Stabilization: fix runtime issues and verify critical workflows with tests.

## Admin Panel Migration Notes
- Preserve the staff guard configuration and ensure panel auth uses the staff guard.
- Replace deprecated Filament APIs and component tags with v5 equivalents.
- Update resource `form`, `table`, and `infolist` definitions to v5 signature changes.
- Update widgets, dashboard pages, and custom pages to v5 page contracts.

## Storefront (Livewire)
- Keep existing routes and component structure.
- Update any coupling to admin-specific Filament APIs.
- Ensure product listing, product detail, cart, and checkout flows render correctly.
- Apply a consistent Tailwind v4 design without redesigning the information architecture.

## Testing & Verification
- Feature test: admin login and product create/edit flow.
- Feature test: storefront product renders on listing and detail view.
- Run focused tests only: `php artisan test --compact <file>`.

## Risks
- Broad API surface area: numerous resources and custom Blade components may require API-by-API adjustments.
- Hidden Filament v3 references in custom components or helpers.
- Storefront regressions due to model or formatting changes.

## Rollout
- Ship in phases; ensure core workflows pass before expanding fixes.
- Keep scope tight to migration, avoiding unrelated refactors.

## Open Questions
- None. Proceed with migration and storefront alignment as defined.
