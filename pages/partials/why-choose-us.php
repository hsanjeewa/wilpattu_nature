<?php
/**
 * Why Choose Us Section
 */
$features = getFeatures();
?>
<section id="about" class="py-20 md:py-28 bg-cream">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-orange-accent text-sm font-semibold uppercase tracking-widest mb-4 block">Why Choose Us</span>
            <h2 class="font-playfair text-4xl md:text-5xl font-bold text-dark-brown mb-6">
                The Premier Safari Experience
            </h2>
            <p class="text-gray-600 text-lg leading-relaxed">
                Wilpattu National Park is Sri Lanka's largest and one of the world's best destinations for leopard sightings. With fewer crowds than other parks, enjoy a peaceful, intimate encounter with nature's most magnificent creatures.
            </p>
        </div>
        
        <!-- Features Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($features as $index => $feature): ?>
                <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-lg transition-shadow duration-300">
                    <!-- Icon -->
                    <div class="w-12 h-12 bg-dark-green/10 rounded-lg flex items-center justify-center mb-4">
                        <i data-lucide="<?php echo $feature['icon']; ?>" class="w-6 h-6 text-dark-green"></i>
                    </div>
                    
                    <!-- Content -->
                    <h3 class="font-playfair text-xl font-semibold text-dark-brown mb-2">
                        <?php echo e($feature['title']); ?>
                    </h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        <?php echo e($feature['description']); ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
