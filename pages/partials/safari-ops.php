<?php
/**
 * Safari Operations Section
 */
$inclusions = getSafariInclusions();
?>
<section class="py-16 md:py-20 bg-cream">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Operations Info -->
        <div class="text-center mb-12">
            <h3 class="font-playfair text-2xl md:text-3xl font-bold text-dark-brown mb-4">
                Safari Operations
            </h3>
            <p class="text-gray-600 text-lg max-w-3xl mx-auto">
                We provide safaris from <span class="text-dark-green font-semibold">Eluwankulama</span> and <span class="text-dark-green font-semibold">Hunuwilagama</span> entrances.
            </p>
            <p class="text-gray-500 text-sm mt-3 max-w-2xl mx-auto">
                <span class="font-semibold">Note:</span> Free pickup and drop will be provided from Hunuwilgama to park. Additional charge will be added for pickups and drops from Eluwankulama entrance.
            </p>
        </div>
        
        <!-- Safari Inclusions -->
        <div class="bg-white rounded-2xl p-8 md:p-10 shadow-sm">
            <h4 class="font-playfair text-xl font-bold text-dark-brown mb-6 text-center">
                Safari Inclusions
            </h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach ($inclusions as $inclusion): ?>
                    <div class="flex items-center gap-3 p-3 bg-cream/50 rounded-lg">
                        <div class="w-8 h-8 bg-dark-green/10 rounded-full flex items-center justify-center flex-shrink-0">
                            <i data-lucide="check" class="w-4 h-4 text-dark-green"></i>
                        </div>
                        <span class="text-gray-700 text-sm"><?php echo e($inclusion); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
