<?php
/**
 * Safari Packages Section
 */
$db = Database::getInstance();
$packages = $db->getPackages();
?>
<section id="safaris" class="py-20 md:py-28 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-orange-accent text-sm font-semibold uppercase tracking-widest mb-4 block">Our Packages</span>
            <h2 class="font-playfair text-4xl md:text-5xl font-bold text-dark-brown mb-6">
                Choose Your Adventure
            </h2>
            <p class="text-gray-600 text-lg leading-relaxed">
                From day trips to overnight stays inside the park, we have the perfect safari experience waiting for you.
            </p>
        </div>
        
        <!-- Packages Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($packages as $package): ?>
                <div class="package-card bg-white rounded-2xl overflow-hidden border border-gray-100 <?php echo $package['is_popular'] ? 'ring-2 ring-primary' : ''; ?>">
                    <!-- Image -->
                    <div class="relative h-56 overflow-hidden">
                        <img src="<?php echo image('packages/' . $package['image']); ?>" 
                             alt="<?php echo e($package['name']); ?>" 
                             class="w-full h-full object-cover"
                             onerror="this.src='https://via.placeholder.com/600x400?text=<?php echo urlencode($package['name']); ?>'">
                        
                        <!-- Time Badge -->
                        <div class="absolute bottom-4 left-4 bg-dark-brown/80 backdrop-blur-sm text-white text-xs font-medium px-3 py-1.5 rounded-full flex items-center gap-1.5">
                            <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                            <?php echo e($package['duration']); ?>
                        </div>
                        
                        <!-- Popular Badge -->
                        <?php if ($package['is_popular']): ?>
                            <div class="absolute top-4 right-4 bg-primary text-dark-brown text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider">
                                Most Popular
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Content -->
                    <div class="p-6">
                        <!-- Subtitle -->
                        <span class="text-orange-accent text-xs font-semibold uppercase tracking-wider">
                            <?php echo e($package['subtitle']); ?>
                        </span>
                        
                        <!-- Title -->
                        <h3 class="font-playfair text-2xl font-bold text-dark-brown mt-1 mb-4">
                            <?php echo e($package['name']); ?>
                        </h3>
                        
                        <!-- Features -->
                        <ul class="space-y-2 mb-6">
                            <?php foreach ($package['features'] as $feature): ?>
                                <li class="flex items-start gap-2 text-gray-600 text-sm">
                                    <i data-lucide="check" class="w-4 h-4 text-dark-green mt-0.5 flex-shrink-0"></i>
                                    <?php echo e($feature); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        
                        <!-- Price & CTA -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <div>
                                <span class="text-gray-500 text-xs uppercase tracking-wider">Starting from</span>
                                <div class="flex items-baseline gap-1">
                                    <span class="font-playfair text-3xl font-bold text-dark-brown"><?php echo formatPrice($package['price']); ?></span>
                                    <span class="text-gray-500 text-sm">/person</span>
                                </div>
                            </div>
                            <a href="index.php?page=home#contact" class="inline-flex items-center gap-2 bg-dark-green hover:bg-dark-green-light text-white font-medium px-5 py-2.5 rounded-lg transition-colors">
                                Book Now
                                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
