<?php
/**
 * Hero Section
 */
?>
<section id="home" class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <!-- Background Image -->
    <div class="absolute inset-0">
        <img src="<?php echo image('hero/hero-elephant.webp'); ?>" 
              alt="Elephant in Wilpattu National Park" 
              class="w-full h-full object-cover"
              onerror="this.src='https://images.unsplash.com/photo-1557050543-4d5f4e07ef46?w=1920'">
        <!-- Overlay -->
        <div class="absolute inset-0 hero-overlay"></div>
    </div>
    
    <!-- Content -->
    <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center pt-20">
        
        <!-- Badge -->
        <div class="inline-flex items-center gap-2 bg-dark-green/60 backdrop-blur-sm border border-white/20 rounded-full px-4 py-2 mb-8">
            <i data-lucide="map-pin" class="w-4 h-4 text-primary"></i>
            <span class="text-white/90 text-sm font-medium">Sri Lanka's Largest National Park</span>
        </div>
        
        <!-- Main Heading -->
        <h1 class="font-playfair text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold text-white mb-6 leading-tight">
            Explore the<br>
            <span class="text-primary">Wilderness of Ceylon</span>
        </h1>
        
        <!-- Subtitle -->
        <p class="text-white/80 text-lg md:text-xl max-w-2xl mx-auto mb-10 leading-relaxed">
            Witness majestic leopards in their natural habitat with our expert-guided safaris. Over 90% leopard sighting rate in Wilpattu National Park.
        </p>
        
        <!-- Stats -->
        <div class="flex flex-wrap justify-center gap-8 md:gap-16 mb-12">
            <div class="text-center">
                <div class="text-primary font-playfair text-4xl md:text-5xl font-bold">90%+</div>
                <div class="text-white/70 text-sm uppercase tracking-wider mt-1">Leopard Sightings</div>
            </div>
            <div class="text-center">
                <div class="text-primary font-playfair text-4xl md:text-5xl font-bold">7+</div>
                <div class="text-white/70 text-sm uppercase tracking-wider mt-1">Years Experience</div>
            </div>
            <div class="text-center">
                <div class="text-primary font-playfair text-4xl md:text-5xl font-bold flex items-center justify-center gap-1">
                    5.0
                    <i data-lucide="star" class="w-6 h-6 fill-primary text-primary"></i>
                </div>
                <div class="text-white/70 text-sm uppercase tracking-wider mt-1">Rating</div>
            </div>
        </div>
        
        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center mb-16">
            <a href="index.php?page=home#contact" class="btn-primary inline-flex items-center justify-center gap-2 bg-primary text-dark-brown font-semibold px-8 py-4 rounded-lg text-lg">
                Book Your Safari
                <i data-lucide="arrow-right" class="w-5 h-5"></i>
            </a>
            <a href="index.php?page=home#safaris" class="btn-outline inline-flex items-center justify-center gap-2 border-2 border-primary text-primary font-semibold px-8 py-4 rounded-lg text-lg hover:bg-primary/10">
                View Packages
            </a>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="animate-bounce">
            <a href="index.php?page=home#about" class="inline-flex flex-col items-center text-white/60 hover:text-primary transition-colors">
                <span class="text-xs uppercase tracking-widest mb-2">Scroll to Explore</span>
                <i data-lucide="chevron-down" class="w-6 h-6"></i>
            </a>
        </div>
    </div>
</section>
