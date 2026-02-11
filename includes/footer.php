<?php
$footerLinks = getFooterLinks();
?>
    
    <!-- Footer -->
    <footer class="bg-dark-brown text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
                
                <!-- Brand Column -->
                <div class="lg:col-span-1">
                    <a href="index.php" class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-full bg-primary flex items-center justify-center overflow-hidden">
                            <img src="<?php echo image('logo.png'); ?>" alt="Wilpattu Nature" class="w-full h-full object-cover" onerror="this.src='https://via.placeholder.com/48?text=WN'">
                        </div>
                        <div>
                            <span class="font-playfair text-xl font-bold block">Wilpattu Nature</span>
                            <span class="text-primary text-sm">Sri Lanka's Premier Safari Experience</span>
                        </div>
                    </a>
                    <p class="text-white/70 text-sm leading-relaxed mb-6">
                        Experience the untamed beauty of Sri Lanka's largest national park. With over 7 years of expertise, we deliver unforgettable wildlife encounters and create lasting memories.
                    </p>
                    <!-- Social Icons -->
                    <div class="flex gap-3">
                        <a href="<?php echo FACEBOOK_URL; ?>" target="_blank" class="w-10 h-10 rounded-full bg-white/10 hover:bg-primary flex items-center justify-center transition-colors">
                            <i data-lucide="facebook" class="w-5 h-5"></i>
                        </a>
                        <a href="<?php echo INSTAGRAM_URL; ?>" target="_blank" class="w-10 h-10 rounded-full bg-white/10 hover:bg-primary flex items-center justify-center transition-colors">
                            <i data-lucide="instagram" class="w-5 h-5"></i>
                        </a>
                        <a href="<?php echo TWITTER_URL; ?>" target="_blank" class="w-10 h-10 rounded-full bg-white/10 hover:bg-primary flex items-center justify-center transition-colors">
                            <i data-lucide="twitter" class="w-5 h-5"></i>
                        </a>
                        <a href="<?php echo YOUTUBE_URL; ?>" target="_blank" class="w-10 h-10 rounded-full bg-white/10 hover:bg-primary flex items-center justify-center transition-colors">
                            <i data-lucide="youtube" class="w-5 h-5"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h4 class="font-playfair text-lg font-semibold mb-6">Quick Links</h4>
                    <ul class="space-y-3">
                        <?php foreach ($footerLinks['quick'] as $link): ?>
                            <li>
                                <a href="<?php echo $link['url']; ?>" class="text-white/70 hover:text-primary text-sm transition-colors">
                                    <?php echo e($link['label']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <!-- Our Safaris -->
                <div>
                    <h4 class="font-playfair text-lg font-semibold mb-6">Our Safaris</h4>
                    <ul class="space-y-3">
                        <?php foreach ($footerLinks['safaris'] as $link): ?>
                            <li>
                                <a href="<?php echo $link['url']; ?>" class="text-white/70 hover:text-primary text-sm transition-colors">
                                    <?php echo e($link['label']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <!-- Contact Info -->
                <div>
                    <h4 class="font-playfair text-lg font-semibold mb-6">Contact Us</h4>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <i data-lucide="phone" class="w-5 h-5 text-primary mt-0.5"></i>
                            <div>
                                <p class="text-white/70 text-sm"><?php echo PHONE_PRIMARY; ?></p>
                                <p class="text-white/70 text-sm"><?php echo PHONE_SECONDARY; ?></p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <i data-lucide="mail" class="w-5 h-5 text-primary mt-0.5"></i>
                            <div>
                                <p class="text-white/70 text-sm"><?php echo EMAIL_PRIMARY; ?></p>
                                <p class="text-white/70 text-sm"><?php echo EMAIL_SECONDARY; ?></p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <i data-lucide="map-pin" class="w-5 h-5 text-primary mt-0.5"></i>
                            <p class="text-white/70 text-sm"><?php echo ADDRESS; ?></p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Bottom Bar -->
        <div class="border-t border-white/10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-white/50 text-sm">
                        &copy; <?php echo date('Y'); ?> Wilpattu Nature. All rights reserved.
                    </p>
                    <p class="text-white/50 text-sm flex items-center gap-1">
                        Made with <i data-lucide="heart" class="w-4 h-4 text-red-500 fill-red-500"></i> in Sri Lanka
                    </p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', WHATSAPP_NUMBER); ?>?text=Hello! I'm interested in booking a safari at Wilpattu Nature." 
       target="_blank"
       class="fixed bottom-6 right-6 z-50 group"
       x-data="{ showTooltip: false }"
       @mouseenter="showTooltip = true"
       @mouseleave="showTooltip = false">
        
        <!-- Tooltip -->
        <div x-show="showTooltip" 
             x-transition
             class="absolute right-full mr-3 top-1/2 -translate-y-1/2 bg-white text-gray-800 px-4 py-2 rounded-lg shadow-lg whitespace-nowrap text-sm font-medium"
             x-cloak>
            Chat with us!
            <div class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-1 w-2 h-2 bg-white rotate-45"></div>
        </div>
        
        <!-- Button -->
        <div class="w-14 h-14 bg-green-500 hover:bg-green-600 rounded-full flex items-center justify-center shadow-lg transition-all duration-300 hover:scale-110">
            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
        </div>
    </a>
    
    <!-- Initialize Lucide Icons -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>
    
</body>
</html>
