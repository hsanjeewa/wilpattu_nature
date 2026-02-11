<?php
/**
 * Wildlife Wonders Gallery Section
 */
$db = Database::getInstance();
$featuredImages = $db->getGalleryImages(true, 7);
?>
<section id="gallery" class="py-20 md:py-28 bg-dark-brown">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-primary text-sm font-semibold uppercase tracking-widest mb-4 block">Featured Photos</span>
            <h2 class="font-playfair text-4xl md:text-5xl font-bold text-white mb-6">
                Wildlife Wonders
            </h2>
            <p class="text-white/70 text-lg leading-relaxed">
                Discover the breathtaking beauty of Wilpattu through our curated collection of featured wildlife photography and unforgettable safari moments.
            </p>
        </div>
        
        <!-- Masonry Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 auto-rows-[200px]">
            
            <!-- Large Image (Left) -->
            <?php if (isset($featuredImages[0])): ?>
                <div class="gallery-item col-span-2 row-span-2 rounded-xl overflow-hidden relative group cursor-pointer"
                     @click="$dispatch('open-lightbox', { index: 0 })">
                    <img src="<?php echo image('gallery/' . $featuredImages[0]['filename']); ?>" 
                         alt="<?php echo e($featuredImages[0]['title']); ?>"
                         class="w-full h-full object-cover"
                         onerror="this.src='https://via.placeholder.com/800x800?text=Wildlife'">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                        <span class="text-white font-medium"><?php echo e($featuredImages[0]['title']); ?></span>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Top Right -->
            <?php if (isset($featuredImages[1])): ?>
                <div class="gallery-item rounded-xl overflow-hidden relative group cursor-pointer"
                     @click="$dispatch('open-lightbox', { index: 1 })">
                    <img src="<?php echo image('gallery/' . $featuredImages[1]['filename']); ?>" 
                         alt="<?php echo e($featuredImages[1]['title']); ?>"
                         class="w-full h-full object-cover"
                         onerror="this.src='https://via.placeholder.com/400x200?text=Safari'">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-3">
                        <span class="text-white text-sm font-medium"><?php echo e($featuredImages[1]['title']); ?></span>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Middle Right (Large) -->
            <?php if (isset($featuredImages[2])): ?>
                <div class="gallery-item col-span-1 md:col-span-1 row-span-2 rounded-xl overflow-hidden relative group cursor-pointer"
                     @click="$dispatch('open-lightbox', { index: 2 })">
                    <img src="<?php echo image('gallery/' . $featuredImages[2]['filename']); ?>" 
                         alt="<?php echo e($featuredImages[2]['title']); ?>"
                         class="w-full h-full object-cover"
                         onerror="this.src='https://via.placeholder.com/400x400?text=Nature'">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-3">
                        <span class="text-white text-sm font-medium"><?php echo e($featuredImages[2]['title']); ?></span>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Bottom Row Images -->
            <?php for ($i = 3; $i < 7; $i++): ?>
                <?php if (isset($featuredImages[$i])): ?>
                    <div class="gallery-item rounded-xl overflow-hidden relative group cursor-pointer"
                         @click="$dispatch('open-lightbox', { index: <?php echo $i; ?> })">
                        <img src="<?php echo image('gallery/' . $featuredImages[$i]['filename']); ?>" 
                             alt="<?php echo e($featuredImages[$i]['title']); ?>"
                             class="w-full h-full object-cover"
                             onerror="this.src='https://via.placeholder.com/400x200?text=Wildlife'">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-3">
                            <span class="text-white text-sm font-medium"><?php echo e($featuredImages[$i]['title']); ?></span>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
        
        <!-- View All Button -->
        <div class="text-center mt-10">
            <a href="index.php?page=gallery" class="inline-flex items-center gap-2 border-2 border-primary text-primary hover:bg-primary hover:text-dark-brown font-semibold px-8 py-3 rounded-lg transition-all duration-300">
                View All Photos
                <i data-lucide="arrow-right" class="w-5 h-5"></i>
            </a>
        </div>
    </div>
</section>
