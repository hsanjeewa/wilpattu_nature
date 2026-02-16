AGENTS.md
===========

Purpose
-------
This file is a short, practical guide for automated coding agents working in this repository. It documents how to build/test/lint, where to find important files, and the coding conventions to follow.

Quick commands
--------------
**Run all tests:**
```bash
php tests/validate.php              # CLI validation suite (exit code 0 = success)
```

**Browser-based tests:**
```
http://localhost/tests/             # Visual test results in browser
```

**Syntax checks:**
```bash
php -l path/to/file.php             # Syntax check a single file
php -l includes/db.php              # Example: Check db.php
```

**Run a single test (ad-hoc examples):**
```bash
# Check PHP version requirement
php -r "require 'config.php'; echo (PHP_VERSION_ID >= 80000 ? 'PASS\n' : 'FAIL\n');"

# Test database connection
php -r "require 'config.php'; require 'includes/db.php'; try { Database::getInstance(); echo 'DB OK\n'; } catch (Exception \$e) { echo 'DB FAIL: '.\$e->getMessage().'\n'; }"

# Verify CSRF token function
php -r "require 'config.php'; require 'includes/functions.php'; echo (function_exists('verifyCsrfToken') ? 'PASS\n' : 'FAIL\n');"

# Check SQLite extension
php -r "echo (extension_loaded('sqlite3') ? 'PASS\n' : 'FAIL\n');"

# Test email validation
php -r "require 'includes/functions.php'; echo (isValidEmail('test@example.com') ? 'PASS\n' : 'FAIL\n');"
```

Repository basics
-----------------
- **Language**: PHP 8.0+ (no Composer - intentionally dependency-free except bundled PHPMailer)
- **Database**: SQLite3 (database/wilpattu.db) - auto-created by Database class on first run
- **Frontend**: TailwindCSS and AlpineJS via CDN - no build step, no npm/webpack
- **Email**: PHPMailer (bundled in includes/PHPMailer/) for SMTP email with attachment support
- **Environment**: .env file loaded via custom dotenv.php (see includes/dotenv.php)

Where things live
------------------
- index.php                — main router/entry
- config.php               — global configuration and constants
- includes/                — shared PHP helpers and DB class (db.php, functions.php, header.php, footer.php)
- pages/                   — page templates and partials
- api/                     — JSON endpoints (booking.php)
- database/                — schema.sql and wilpattu.db
- assets/                  — image/css/js
- tests/                   — CLI/browser validation helpers (tests/validate.php, tests/index.php)

Testing & validation
---------------------
- Primary test runner (repo-provided):

  php tests/validate.php

  This script performs environment and content checks (PHP version, extensions, directory layout, DB tables, sample data, images, etc.). It exits 0 on success, non-zero on failure.

- To run a single assertion quickly, use an ad-hoc PHP one-liner as shown above ("Check PHP version" / "Check DB connection"). For more targeted checks modify or extract the specific test from tests/validate.php.

Linting & formatting
---------------------
- No explicit linter or formatter configuration checked into repository (no .php-cs-fixer.*, .phpcs.xml, .editorconfig, or .prettierrc found).
- Recommended quick checks for agents before committing code:

  # Syntax check
  php -l path/to/file.php

  # Optional (recommended) tools to adopt if you will be making many changes:
  - php-cs-fixer (PSR-12) — enforce coding style
  - phpcs + ruleset (PHP_CodeSniffer) — detect style issues
  - phpstan (static analysis) — catch type/safety issues

Style guide (conventions observed)
---------------------------------
The project uses a pragmatic, simple PHP style. Agents must follow existing patterns where possible.

- Files & layout:
  - Keep shared logic in includes/ (db.php, functions.php). Page templates belong in pages/ and pages/partials/.

- PHP versions & runtime:
  - Target: PHP 8.0+. Use typed features conservatively unless updating the entire codebase.

- Naming (STRICT - follow exactly):
  - Classes: PascalCase (Database, Validator)
  - Functions: camelCase (getPackages, saveBooking, isValidEmail, formatPrice, verifyCsrfToken)
  - Constants: UPPER_SNAKE_CASE (DB_PATH, SITE_NAME, SMTP_HOST, BOOKING_RECIPIENT)
  - Files: lowercase with hyphens/underscores (booking.php, validate.php, safari-ops.php)
  - Variables: camelCase or snake_case (existing code uses both - match the surrounding context)

- Imports & requires:
  - Use `require_once __DIR__ . '/relative/path.php';` for all includes
  - Example: `require_once __DIR__ . '/../config.php';` (from api/booking.php)
  - Example: `require_once __DIR__ . '/includes/db.php';` (from index.php)
  - NO namespaces, NO use statements, NO Composer autoload (intentional simplicity)
  - Load order: config.php → db.php → functions.php → other files

- Indentation & formatting:
  - Existing files use 4-space indentation. Continue with 4-space indent for new PHP files.
  - Keep short functions. Use early returns for validation failures (current pattern in api/booking.php).

- Error handling:
  - Use try/catch for operations that may throw (Database methods, email sending, external APIs)
  - Example: `try { $db->saveBooking($data); } catch (Exception $e) { /* handle */ }`
  - API endpoints: return early with specific error messages and HTTP status codes
  - Example: `http_response_code(400); echo json_encode(['success' => false, 'errors' => $errors]);`
  - Return user-friendly messages to clients; log technical details server-side
  - NEVER use @ error suppression operator (one legacy exception in email.php - don't add more)

- Security & sanitization (CRITICAL):
  - Output escaping: Use `e($string)` helper (alias for htmlspecialchars with ENT_QUOTES, UTF-8)
  - Example: `echo e($user_input);` NOT `echo $user_input;`
  - SQL: ALWAYS use prepared statements with bindValue (see Database::getPackage example)
  - Example: `$stmt->bindValue(':id', $id, SQLITE3_INTEGER);`
  - CSRF: Verify tokens on ALL state-changing operations
  - Example: `if (!verifyCsrfToken($input['csrf_token'])) { /* reject */ }`
  - Input sanitization: `htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8')` before DB storage

- Database interactions:
  - ALWAYS use Database::getInstance() singleton - never create raw SQLite3 instances
  - Example: `$db = Database::getInstance(); $packages = $db->getPackages();`
  - JSON columns: Decode on read, encode on write (see packages.features, gallery_images metadata)
  - Example: `$row['features'] = json_decode($row['features'], true);`
  - Use SQLITE3_ASSOC for associative arrays, SQLITE3_INTEGER/TEXT for type binding
  - Call $db->init() only during initial setup - tables auto-created from schema.sql

- Tests & images:
  - tests/validate.php expects certain images and sample data to be present. If you modify image filenames or package counts, update the validator accordingly.

Agent workflow (practical rules)
-------------------------------
**Before making changes:**
1. Read the files you will modify and 2-3 similar files for style reference
2. Check includes/ for existing helper functions before writing new ones
3. Review pages/partials/ for reusable components before duplicating logic

**After making changes:**
1. Syntax check: `php -l path/to/modified-file.php`
2. If DB/config changed: `php tests/validate.php`
3. If API changed: Test the endpoint manually or write an ad-hoc test
4. Check for type errors, undefined variables, missing requires

**Before committing:**
- Verify all tests pass: `php tests/validate.php` (exit code 0)
- Check you haven't introduced @ error suppression or raw SQLite3 instances
- Confirm CSRF protection on new forms/API endpoints
- Do NOT commit unless explicitly requested by the user

**Modernisation:**
- Do NOT add Composer, npm, webpack, or build tools without approval
- This project intentionally avoids package managers (except bundled PHPMailer)
- If proposing namespaces/PSR-4/phpstan, create a migration plan and request review first

Cursor / Copilot rules
-----------------------
- No .cursor/rules/ or .cursorrules files were found in the repository.
- No .github/copilot-instructions.md was found.

If you maintain custom Cursor or Copilot rules elsewhere, add a short note here and ensure agents load them before making large changes.

Safety & anti-patterns (DO NOT)
-------------------------------
- Do not use `@` error suppression.
- Do not use global state without clear encapsulation (avoid adding more global variables into config.php).
- Do not disable CSRF, XSS, or prepared statements.
- Do not commit secrets (API keys, production DB files). Keep wilpattu.db out of VCS in real deployments.

Extending this file
--------------------
If you add tooling (php-cs-fixer, phpstan, composer.json), update this AGENTS.md with exact commands and a one-line example for running a single test or a targeted check.

Contact Points
--------------
- Author / Maintainer: see README.md and PROJECT_SUMMARY.md for contact and deployment notes.

---
Generated by an automated agent as the repository initialization guide. Update as the repo gains more automation (composer, CI, linters).
