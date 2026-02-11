<?php
/**
 * Home Page - Assembles all sections
 */
$pageTitle = SITE_NAME . ' | Sri Lanka\'s Premier Wildlife Experience';

include __DIR__ . '/../includes/header.php';
?>

<!-- Hero Section -->
<?php include __DIR__ . '/partials/hero.php'; ?>

<!-- Why Choose Us Section -->
<?php include __DIR__ . '/partials/why-choose-us.php'; ?>

<!-- Safari Packages Section -->
<?php include __DIR__ . '/partials/packages.php'; ?>

<!-- Safari Operations Section -->
<?php include __DIR__ . '/partials/safari-ops.php'; ?>

<!-- Wildlife Wonders Gallery Section -->
<?php include __DIR__ . '/partials/wildlife-wonders.php'; ?>

<!-- Contact / Booking Section -->
<?php include __DIR__ . '/partials/contact.php'; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
