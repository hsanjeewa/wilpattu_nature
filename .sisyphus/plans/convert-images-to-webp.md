# Plan: Convert Home Page JPG images to WebP

Summary
-------
Convert all JPG/JPEG images that are actually referenced by the Home page (hero section, package cards, and featured gallery images) to WebP using the cwebp tool. Keep original JPG files as backups. Update runtime image selection so the site serves WebP when available (non-destructive change). Verify pages render correctly and that generated WebP images are smaller (or comparable) in size.

Scope
-----
- Convert these image groups:
  1. assets/images/hero/hero-elephant.jpg
  2. assets/images/packages/* (only filenames referenced by records in `packages` table)
  3. assets/images/gallery/* (only filenames referenced by `gallery_images` for featured images used on home page)
- Do NOT convert images that are not referenced on the Home page for this plan.
- Keep original JPG/JPEG files.

Assumptions
-----------
- cwebp is available on the machine where conversion will run (Linux/Unix environment).
- The webserver will serve .webp files without extra config (static file serving via Apache/Nginx works by file extension).
- Database access is available from the repository environment (we can run small PHP scripts or sqlite3 queries to list filenames).

Acceptance criteria
-------------------
1. For every referenced JPG/JPEG file an equivalent .webp file exists in the same directory.
2. The helper image() will prefer the .webp file when present so runtime code does not require DB edits.
3. All updated pages load without broken images; fallback behaviour in place (original JPGs still present).
4. Converted files are validated (file exists & readable) and sizes are reported.
5. A short report is saved to .sisyphus/reports/convert-images-to-webp-<timestamp>.log with a summary.

Tools required
--------------
- cwebp (from libwebp) — required
  - Example install (Debian/Ubuntu): sudo apt-get install webp
- php CLI (for database queries & quick verification)
- sqlite3 CLI (optional)

Work steps
----------
1. Pre-checks
   - Verify current working directory is repository root.
   - Confirm cwebp availability: `cwebp -version`.
   - Ensure assets/images directories are writable by the user running the conversion.

2. Enumerate filenames that need conversion
   - Hero: `assets/images/hero/hero-elephant.jpg` (hard-coded in pages/partials/hero.php)
   - Packages: query DB to list `image` column values from `packages` table.
     - PHP one-liner (repo root):
       php -r "require 'config.php'; require 'includes/db.php'; \$db = Database::getInstance(); \$pkgs = \$db->getPackages(); foreach(\$pkgs as \$p) echo 'assets/images/packages/'.\$p['image']."\n";"
   - Featured gallery images used on home page: query gallery images where `is_featured=1` and limit used by home page.
     - PHP one-liner:
       php -r "require 'config.php'; require 'includes/db.php'; \$db = Database::getInstance(); \$imgs = \$db->getGalleryImages(true, 7); foreach(\$imgs as \$i) echo 'assets/images/gallery/'.\$i['filename']."\n";"
   - Combine the lists and filter to files that exist on disk; ignore missing files but log them.

3. Backup & safety
   - We will not delete original files. Optionally create a small manifest: `.sisyphus/convert-manifest-<timestamp>.json` listing originals and target webp names.

4. Conversion commands
   - For each existing JPG/JPEG file found run:
     cwebp -q 80 "<input.jpg>" -o "<output.webp>"
   - Use `-q 80` (balanced quality) as requested.
   - If you want lossless for specific images, `-lossless` can be used (not in the default plan).
   - Example: cwebp -q 80 assets/images/hero/hero-elephant.jpg -o assets/images/hero/hero-elephant.webp

5. Verification
   - Confirm .webp files exist and are readable: `file` (or PHP `is_readable()`)
   - Compare sizes: log original vs webp size (bytes) to report
   - Optional: quick visual check by opening in a browser or using `identify`/`magick` if available

6. Make runtime non-destructive preference change (recommended)
   - Instead of changing DB entries (which is destructive), modify `includes/functions.php` `image($path)` helper to prefer a .webp variant when present.
   - Suggested code change (pseudo):

     function image($path) {
         $root = __DIR__ . '/../assets/images/';
         $full = $root . ltrim($path, '/');
         $ext = pathinfo($full, PATHINFO_EXTENSION);
         $base = substr($full, 0, - (strlen($ext) + 1));
         $webp = $base . '.webp';
         if (file_exists($webp)) {
             // return web-accessible path
             return 'assets/' . substr($webp, strlen(__DIR__ . '/../'));
         }
         return 'assets/' . ltrim($path, '/');
     }

   - This approach leaves DB unchanged and allows gradual rollout (if some images fail conversion, the original JPG is still served).

7. Update code / templates (optional)
   - If you prefer to update DB `image` filenames (to `.webp`) that is an alternative. If chosen, prepare SQL UPDATE statements and backup the DB before changes.

8. Report & cleanup
   - Save conversion report to `.sisyphus/reports/convert-images-to-webp-<timestamp>.log` including per-file size changes and any errors.
   - Do NOT delete original JPG files.

9. Acceptance verification
   - Load home page in a browser (or use tests/index.php) and confirm images load (no broken links).
   - Run `php tests/validate.php` to ensure no server-side issues introduced (note: this tests presence of original images — if validator expects original names, keep them in place).

Edge cases & guardrails
-----------------------
- Filenames in DB may already be .webp or point to external URLs. Only convert local JPG/JPEG files.
- Files with spaces or special characters: ensure commands quote paths.
- If a file conversion fails, log and continue; do not stop the entire batch.
- Do not change file ownership/permissions; maintain existing permissions where possible.
- Conversions should be idempotent (re-running should overwrite .webp with same output) — use consistent options.

Rollbacks
---------
- If runtime issues occur, revert the `includes/functions.php` helper to the previous version and remove the generated .webp files (keep the manifest to know what to delete).
- If DB changes were made (not recommended), restore from DB backup.

Deliverables
------------
1. `.sisyphus/plans/convert-images-to-webp.md` (this plan)
2. Manifest file: `.sisyphus/convert-manifest-<timestamp>.json` (created at runtime)
3. Report file: `.sisyphus/reports/convert-images-to-webp-<timestamp>.log`
4. (Optional) Patch for `includes/functions.php` to prefer WebP

Estimated time
--------------
- Discovery & enumerating filenames: 2-5 minutes
- Conversion (depends on number of images and machine): ~1-2 minutes per 10 images on a modest VM
- Runtime change & verification: 10-20 minutes

Who should run this
--------------------
- A developer with CLI access to the repository and ability to run cwebp.

Notes for the executor (atlas delegation format)
----------------------------------------------
1. TASK: Convert referenced home-page JPG images to WebP and make runtime serve WebP when available.
2. EXPECTED OUTCOME: Per acceptance criteria above (webp files present, image() prefers webp, report logged).
3. REQUIRED TOOLS: cwebp, php CLI, sqlite3 (optional)
4. MUST DO: Quote paths, keep originals, produce manifest and report, do not change DB unless explicitly chosen, ensure graceful fallback.
5. MUST NOT DO: Delete originals; alter unrelated image files; change DB without backup; remove CSRF or other security features.
6. CONTEXT: Repo root /home/harsha/projects/wils. Files: includes/functions.php, pages/partials/hero.php, pages/partials/packages.php, pages/partials/wildlife-wonders.php, database schema and contents.
