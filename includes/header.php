<?php
require_once __DIR__ . '/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle ?? SITE_NAME . ' | Sri Lanka\'s Premier Wildlife Experience'); ?></title>
    
    <!-- Meta Tags -->
    <meta name="description" content="Experience Sri Lanka's largest national park with Wilpattu Nature. Expert-guided safaris with 90%+ leopard sighting rate. Book your adventure today!">
    <meta name="keywords" content="Wilpattu, Safari, Sri Lanka, Leopard, Wildlife, National Park">
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo e(SITE_NAME); ?> | Sri Lanka's Premier Wildlife Experience">
    <meta property="og:description" content="Experience Sri Lanka's largest national park with expert-guided safaris.">
    <meta property="og:type" content="website">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- TailwindCSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#E5A935',
                        'primary-dark': '#D49A2E',
                        'primary-light': '#F5C94C',
                        'dark-green': '#1E3A2F',
                        'dark-green-light': '#2A4A3C',
                        'dark-brown': '#2D2419',
                        cream: '#F5F0E8',
                        'cream-dark': '#E8E0D4',
                        'orange-accent': '#C97C3B',
                    },
                    fontFamily: {
                        'playfair': ['Playfair Display', 'serif'],
                        'inter': ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    
    <!-- AlpineJS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Custom Styles -->
    <style>
        [x-cloak] { display: none !important; }
        
        .font-playfair { font-family: 'Playfair Display', serif; }
        .font-inter { font-family: 'Inter', sans-serif; }
        
        /* Smooth scroll */
        html { scroll-behavior: smooth; }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #2D2419; }
        ::-webkit-scrollbar-thumb { background: #E5A935; border-radius: 4px; }
        
        /* Animation classes */
        .fade-in { opacity: 0; transform: translateY(20px); transition: opacity 0.6s ease, transform 0.6s ease; }
        .fade-in.visible { opacity: 1; transform: translateY(0); }
        
        /* Hero gradient overlay */
        .hero-overlay {
            background: linear-gradient(to bottom, rgba(30, 58, 47, 0.4) 0%, rgba(30, 58, 47, 0.6) 100%);
        }
        
        /* Package card hover */
        .package-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .package-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.15); }
        
        /* Gallery image hover */
        .gallery-item { overflow: hidden; }
        .gallery-item img { transition: transform 0.5s ease; }
        .gallery-item:hover img { transform: scale(1.05); }
        
        /* Button animations */
        .btn-primary { transition: all 0.3s ease; }
        .btn-primary:hover { background-color: #D49A2E; transform: translateY(-2px); }
        
        .btn-outline { transition: all 0.3s ease; }
        .btn-outline:hover { background-color: rgba(229, 169, 53, 0.1); }
    </style>
</head>
<body class="font-inter text-gray-800 antialiased" x-data="{ scrolled: false }" @scroll.window="scrolled = window.scrollY > 50">
    
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-300" 
         :class="scrolled ? 'bg-dark-brown/95 backdrop-blur-sm shadow-lg' : 'bg-transparent'">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Logo -->
                <a href="index.php" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center overflow-hidden">
                        <img src="<?php echo image('logo.png'); ?>" alt="Wilpattu Nature" class="w-full h-full object-cover" onerror="this.src='https://via.placeholder.com/40?text=WN'">
                    </div>
                    <span class="text-white font-playfair text-xl font-bold">Wilpattu Nature</span>
                </a>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center gap-8">
                    <?php foreach (getNavigation() as $nav): ?>
                        <?php if ($nav['page'] !== 'gallery-page'): ?>
                            <a href="<?php echo $nav['url']; ?>" 
                               class="text-white/90 hover:text-primary text-sm font-medium uppercase tracking-wider transition-colors">
                                <?php echo e($nav['label']); ?>
                            </a>
                        <?php else: ?>
                            <a href="<?php echo $nav['url']; ?>" 
                               class="text-white/90 hover:text-primary text-sm font-medium uppercase tracking-wider transition-colors">
                                <?php echo e($nav['label']); ?>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                
                <!-- Book Now Button -->
                <a href="index.php?page=home#contact" class="hidden md:inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-dark-brown font-semibold px-6 py-2.5 rounded-lg transition-all duration-300 hover:-translate-y-0.5">
                    <i data-lucide="phone" class="w-4 h-4"></i>
                    Book Now
                </a>
                
                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" 
                        class="md:hidden text-white p-2"
                        x-data="{ mobileMenuOpen: false }">
                    <i data-lucide="menu" class="w-6 h-6" x-show="!mobileMenuOpen"></i>
                    <i data-lucide="x" class="w-6 h-6" x-show="mobileMenuOpen" x-cloak></i>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div x-data="{ mobileMenuOpen: false }" 
             x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="md:hidden bg-dark-brown/98 backdrop-blur-sm border-t border-white/10"
             x-cloak>
            <div class="px-4 py-4 space-y-3">
                <?php foreach (getNavigation() as $nav): ?>
                    <a href="<?php echo $nav['url']; ?>" 
                       @click="mobileMenuOpen = false"
                       class="block text-white/90 hover:text-primary py-2 text-sm font-medium uppercase tracking-wider">
                        <?php echo e($nav['label']); ?>
                    </a>
                <?php endforeach; ?>
                <a href="index.php?page=home#contact" 
                   class="block w-full text-center bg-primary text-dark-brown font-semibold px-6 py-3 rounded-lg mt-4">
                    Book Now
                </a>
            </div>
        </div>
    </nav>
