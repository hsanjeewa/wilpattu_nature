<?php
/**
 * Gallery Page with Lightbox
 */
$pageTitle = 'Wildlife Gallery | ' . SITE_NAME;

require_once __DIR__ . '/../includes/functions.php';

$db = Database::getInstance();
$galleryImages = $db->getGalleryImages();

include __DIR__ . '/../includes/header.php';
?>

<!-- Gallery Section -->
<section class="py-20 md:py-28 bg-cream min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-orange-accent text-sm font-semibold uppercase tracking-widest mb-4 block">Our Collection</span>
            <h1 class="font-playfair text-4xl md:text-5xl lg:text-6xl font-bold text-dark-brown mb-6">
                Wildlife Gallery
            </h1>
            <p class="text-gray-600 text-lg leading-relaxed">
                Explore breathtaking moments captured in the heart of Sri Lanka's wilderness. Double-click any image to view in full screen.
            </p>
        </div>
        
        <!-- Masonry Gallery Grid -->
        <div class="columns-1 md:columns-2 lg:columns-3 gap-4 space-y-4" x-data="galleryLightbox()">
            <?php foreach ($galleryImages as $index => $image): ?>
                <div class="break-inside-avoid gallery-item rounded-xl overflow-hidden relative group cursor-pointer"
                     @dblclick="openImage(<?php echo $index; ?>)">
                    <img src="<?php echo image('gallery/' . $image['filename']); ?>" 
                         alt="<?php echo e($image['title']); ?>"
                         class="w-full object-cover"
                         loading="lazy"
                         onerror="this.src='https://via.placeholder.com/600x800?text=Wildlife'">
                    
                    <!-- Hover Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4">
                        <span class="text-white font-medium"><?php echo e($image['title']); ?></span>
                        <span class="text-white/70 text-sm flex items-center gap-1 mt-1">
                            <i data-lucide="maximize-2" class="w-4 h-4"></i>
                            Double-click to expand
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <!-- Lightbox Modal -->
            <div x-show="isOpen" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-50 bg-black/95 flex items-center justify-center"
                 @keydown.escape.window="close"
                 x-cloak>
                
                <!-- Close Button -->
                <button @click="close" 
                        class="absolute top-6 right-6 text-white/70 hover:text-white transition-colors z-10">
                    <i data-lucide="x" class="w-8 h-8"></i>
                </button>
                
                <!-- Navigation - Previous -->
                <button @click="prev" 
                        x-show="images.length > 1"
                        class="absolute left-6 top-1/2 -translate-y-1/2 text-white/70 hover:text-white transition-colors z-10">
                    <i data-lucide="chevron-left" class="w-10 h-10"></i>
                </button>
                
                <!-- Navigation - Next -->
                <button @click="next" 
                        x-show="images.length > 1"
                        class="absolute right-6 top-1/2 -translate-y-1/2 text-white/70 hover:text-white transition-colors z-10">
                    <i data-lucide="chevron-right" class="w-10 h-10"></i>
                </button>
                
                <!-- Image Container -->
                <div class="max-w-6xl max-h-[85vh] px-4" @click.outside="close">
                    <img :src="currentImageSrc" 
                         :alt="currentImageTitle"
                         class="max-w-full max-h-[80vh] object-contain rounded-lg"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100">
                    
                    <!-- Image Title -->
                    <p x-text="currentImageTitle" class="text-white text-center mt-4 text-lg font-medium"></p>
                    
                    <!-- Image Counter -->
                    <p x-show="images.length > 1" class="text-white/60 text-center mt-2 text-sm">
                        <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Back to Home -->
        <div class="text-center mt-12">
            <a href="index.php" class="inline-flex items-center gap-2 text-dark-brown hover:text-primary font-medium transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
                Back to Home
            </a>
        </div>
    </div>
</section>

<script>
function galleryLightbox() {
    return {
        isOpen: false,
        currentIndex: 0,
        images: <?php echo json_encode(array_map(function($img) { 
            return [
                'src' => image('gallery/' . $img['filename']),
                'title' => $img['title']
            ]; 
        }, $galleryImages)); ?>,
        
        get currentImageSrc() {
            return this.images[this.currentIndex]?.src || '';
        },
        
        get currentImageTitle() {
            return this.images[this.currentIndex]?.title || '';
        },
        
        openImage(index) {
            this.currentIndex = index;
            this.isOpen = true;
            document.body.style.overflow = 'hidden';
            lucide.createIcons();
        },
        
        close() {
            this.isOpen = false;
            document.body.style.overflow = '';
        },
        
        next() {
            this.currentIndex = (this.currentIndex + 1) % this.images.length;
        },
        
        prev() {
            this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
        }
    }
}

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    const lightbox = document.querySelector('[x-data="galleryLightbox()"]');
    if (lightbox) {
        const alpineData = Alpine.$data(lightbox);
        if (alpineData.isOpen) {
            if (e.key === 'ArrowRight') alpineData.next();
            if (e.key === 'ArrowLeft') alpineData.prev();
        }
    }
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
