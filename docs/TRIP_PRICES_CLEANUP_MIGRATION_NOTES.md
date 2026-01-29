# Trip Prices – Future Cleanup Migration Notes

## Context

The table `trip_prices` now has:

- **price_type** – `ENUM('fixed','by_request')` DEFAULT `'fixed'`
- **price_per_pax_nullable** – `DECIMAL(15,2) NULL` (application reads/writes price here)
- **price_per_pax** – original `DECIMAL(15,2) NOT NULL` (kept for safety; no longer written by app)

## When to Run Cleanup

Run a cleanup migration only after:

1. All application code reads and writes only `price_per_pax_nullable`.
2. All API consumers use the `price_per_pax` field (mapped from `price_per_pax_nullable`) and no system depends on the legacy `price_per_pax` column.
3. Backfill has been verified and data is correct in `price_per_pax_nullable`.

## What the Cleanup Migration Should Do

1. **Do NOT use** `Schema::table()->change()` – this project does not use `doctrine/dbal`.
2. **Option A (simple):**  
   In a new migration, drop the column `price_per_pax` only.
    - Application already uses `price_per_pax_nullable`.
    - No rename needed unless you want the column to be named `price_per_pax` again.
3. **Option B (rename):**  
   If you want the final column to be named `price_per_pax` and nullable:
    - Add a new column `price_per_pax_new` DECIMAL(15,2) NULL.
    - Backfill: `UPDATE trip_prices SET price_per_pax_new = price_per_pax_nullable`.
    - Drop column `price_per_pax`.
    - Drop column `price_per_pax_nullable`.
    - Add column `price_per_pax` DECIMAL(15,2) NULL and backfill from `price_per_pax_new`, then drop `price_per_pax_new` (or use raw SQL to rename if your DB supports it).

Recommendation: **Option A** – just drop `price_per_pax` and keep using `price_per_pax_nullable` in the codebase and API (with the existing accessor so the API still exposes `price_per_pax`).

## Do Not Implement Now

This file is for reference only. Do not implement the cleanup migration until the conditions above are met.
