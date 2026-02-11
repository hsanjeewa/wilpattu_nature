AGENTS.md
===========

Purpose
-------
This file is a short, practical guide for automated coding agents working in this repository. It documents how to build/test/lint, where to find important files, and the coding conventions to follow.

Quick commands
--------------
- Run the full installer/validation test suite (CLI):

  php tests/validate.php

- Run a quick PHP syntax check across the repo:

  php -l file.php        # syntax check a single file

- Run an ad-hoc single-check (examples):

  # Check PHP version meets requirement
  php -r "require 'config.php'; echo (PHP_VERSION_ID >= 80000 ? 'PASS\n' : 'FAIL\n');"

  # Check DB connection
  php -r "require 'config.php'; require 'includes/db.php'; try { Database::getInstance(); echo 'DB OK\n'; } catch (Exception \$e) { echo 'DB FAIL: '.\$e->getMessage().'\n'; }"

Repository basics
-----------------
- Language: PHP 8.x (no Composer manifest detected in repo root)
- DB: SQLite (database/wilpattu.db). The DB is auto-created by the Database class on first run.
- Frontend: TailwindCSS and AlpineJS via CDN — no frontend build step present.

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

- Naming:
  - Classes: PascalCase (Database)
  - Functions: camelCase (getPackages, saveBooking, isValidEmail, formatPrice)
  - Constants: UPPER_SNAKE_CASE (DB_PATH, SITE_NAME)
  - Files: lower-case with hyphens or underscores as currently present (e.g., wilpattu.db, validate.php)

- Imports & requires:
  - Use require_once for shared files (existing pattern). Do not introduce namespaces unless performing a broader modernisation.

- Indentation & formatting:
  - Existing files use 4-space indentation. Continue with 4-space indent for new PHP files.
  - Keep short functions. Use early returns for validation failures (current pattern in api/booking.php).

- Error handling:
  - Use try/catch for operations that may throw (DB initialization and writes use exceptions).
  - Return user-friendly messages in API responses; log details server-side if implementing logging.
  - Do not suppress errors with the @ operator. (Note: sendBookingNotification currently uses @mail — avoid adding more suppression.)

- Security & sanitization:
  - Sanitize output using htmlspecialchars (helper e()).
  - Use prepared statements for DB (Database::saveBooking uses prepared statements). Keep this pattern.
  - Verify CSRF tokens on form submissions and API requests (verifyCsrfToken).

- Database interactions:
  - Use the Database class (Database::getInstance()) rather than creating raw SQLite3 instances across the codebase.
  - Preserve JSON-encoded columns (features) as arrays when reading from DB (existing pattern).

- Tests & images:
  - tests/validate.php expects certain images and sample data to be present. If you modify image filenames or package counts, update the validator accordingly.

Agent workflow (practical rules)
-------------------------------
1. Read the files you will touch. Find similar code in includes/ and pages/partials/ and follow its style.
2. Run quick checks:
   - php -l modified/file.php
   - php tests/validate.php (if your change touches DB or templates)
3. Do not commit on behalf of human maintainers unless explicitly asked.
4. Do not add new runtime dependencies without approval. This project intentionally avoids Composer-managed dependencies.
5. If you introduce modernisation (namespaces, composer, phpstan), create a short plan and ask for review before implementing.

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
