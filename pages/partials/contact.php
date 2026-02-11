<?php
/**
 * Contact / Booking Section
 */
$timings = getSafariTimings();
$db = Database::getInstance();
$packages = $db->getPackages();
?>
<section id="contact" class="py-20 md:py-28 bg-cream">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-orange-accent text-sm font-semibold uppercase tracking-widest mb-4 block">Get In Touch</span>
            <h2 class="font-playfair text-4xl md:text-5xl font-bold text-dark-brown mb-6">
                Book Your Safari Today
            </h2>
            <p class="text-gray-600 text-lg leading-relaxed">
                Ready to experience the wild? Fill out the form below and our team will get back to you within 24 hours.
            </p>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- Left Column - Contact Info -->
            <div class="space-y-6">
                
                <!-- Contact Information Card -->
                <div class="bg-dark-green rounded-2xl p-8 text-white">
                    <h3 class="font-playfair text-2xl font-bold mb-6">Contact Information</h3>
                    
                    <div class="space-y-5">
                        <!-- Phone -->
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i data-lucide="phone" class="w-5 h-5 text-primary"></i>
                            </div>
                            <div>
                                <span class="text-white/60 text-sm block mb-1">Phone</span>
                                <p class="text-white"><?php echo PHONE_PRIMARY; ?></p>
                                <p class="text-white"><?php echo PHONE_SECONDARY; ?></p>
                            </div>
                        </div>
                        
                        <!-- Email -->
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i data-lucide="mail" class="w-5 h-5 text-primary"></i>
                            </div>
                            <div>
                                <span class="text-white/60 text-sm block mb-1">Email</span>
                                <p class="text-white"><?php echo EMAIL_PRIMARY; ?></p>
                                <p class="text-white"><?php echo EMAIL_SECONDARY; ?></p>
                            </div>
                        </div>
                        
                        <!-- Location -->
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i data-lucide="map-pin" class="w-5 h-5 text-primary"></i>
                            </div>
                            <div>
                                <span class="text-white/60 text-sm block mb-1">Location</span>
                                <p class="text-white"><?php echo ADDRESS; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Safari Timings Card -->
                <div class="bg-cream-dark rounded-2xl p-8">
                    <h3 class="font-playfair text-2xl font-bold text-dark-brown mb-6">Safari Timings</h3>
                    
                    <div class="space-y-4">
                        <?php foreach ($timings as $timing): ?>
                            <div class="flex justify-between items-center py-3 border-b border-dark-brown/10 last:border-0">
                                <span class="text-gray-700"><?php echo e($timing['label']); ?></span>
                                <span class="font-semibold text-dark-brown"><?php echo e($timing['time']); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <!-- Right Column - Booking Form -->
            <div class="bg-white rounded-2xl p-8 shadow-sm">
                <h3 class="font-playfair text-2xl font-bold text-dark-brown mb-6">Request Booking</h3>
                
                <form x-data="bookingForm()" @submit.prevent="submitForm" class="space-y-5">
                    <?php echo csrfField(); ?>
                    
                    <!-- Name & Email Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-gray-700 text-sm font-medium mb-2">Full Name</label>
                            <input type="text" 
                                   x-model="form.name" 
                                   @blur="validateField('name')"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all"
                                   placeholder="John Doe"
                                   :class="{ 'border-red-500': errors.name }">
                            <p x-show="errors.name" x-text="errors.name" class="text-red-500 text-xs mt-1" x-cloak></p>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-medium mb-2">Email Address</label>
                            <input type="email" 
                                   x-model="form.email" 
                                   @blur="validateField('email')"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all"
                                   placeholder="john@example.com"
                                   :class="{ 'border-red-500': errors.email }">
                            <p x-show="errors.email" x-text="errors.email" class="text-red-500 text-xs mt-1" x-cloak></p>
                        </div>
                    </div>
                    
                    <!-- Date & Guests Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-gray-700 text-sm font-medium mb-2">Preferred Date</label>
                            <input type="date" 
                                   x-model="form.date" 
                                   @blur="validateField('date')"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all"
                                   :class="{ 'border-red-500': errors.date }">
                            <p x-show="errors.date" x-text="errors.date" class="text-red-500 text-xs mt-1" x-cloak></p>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-medium mb-2">Number of Guests</label>
                            <input type="number" 
                                   x-model="form.guests" 
                                   min="1" 
                                   max="20"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all"
                                   placeholder="2">
                        </div>
                    </div>
                    
                    <!-- Package Selection -->
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">Select Package (Optional)</label>
                        <select x-model="form.package_id" 
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all bg-white">
                            <option value="">Choose a package...</option>
                            <?php foreach ($packages as $package): ?>
                                <option value="<?php echo $package['id']; ?>"><?php echo e($package['name']); ?> - <?php echo formatPrice($package['price']); ?>/person</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Message -->
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">Message (Optional)</label>
                        <textarea x-model="form.message" 
                                  rows="4"
                                  class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all resize-none"
                                  placeholder="Tell us about your safari preferences..."></textarea>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" 
                            :disabled="submitting"
                            class="w-full bg-primary hover:bg-primary-dark text-dark-brown font-bold py-4 rounded-lg transition-all duration-300 flex items-center justify-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed">
                        <span x-show="!submitting" class="flex items-center gap-2">
                            <i data-lucide="send" class="w-5 h-5"></i>
                            SEND BOOKING REQUEST
                        </span>
                        <span x-show="submitting" class="flex items-center gap-2">
                            <i data-lucide="loader-2" class="w-5 h-5 animate-spin"></i>
                            Sending...
                        </span>
                    </button>
                    
                    <!-- Success Message -->
                    <div x-show="success" x-transition class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-center" x-cloak>
                        <div class="flex items-center justify-center gap-2">
                            <i data-lucide="check-circle" class="w-5 h-5"></i>
                            <span>Booking request sent successfully! We'll contact you within 24 hours.</span>
                        </div>
                    </div>
                    
                    <!-- Error Message -->
                    <div x-show="error" x-transition class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-center" x-cloak>
                        <div class="flex items-center justify-center gap-2">
                            <i data-lucide="alert-circle" class="w-5 h-5"></i>
                            <span x-text="error"></span>
                        </div>
                    </div>
                    
                    <p class="text-gray-500 text-sm text-center">
                        We'll respond within 24 hours to confirm your booking.
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
function bookingForm() {
    return {
        form: {
            name: '',
            email: '',
            date: '',
            guests: 2,
            package_id: '',
            message: ''
        },
        errors: {},
        submitting: false,
        success: false,
        error: null,
        
        validateField(field) {
            this.errors[field] = '';
            
            switch(field) {
                case 'name':
                    if (!this.form.name.trim()) {
                        this.errors.name = 'Full name is required';
                    } else if (this.form.name.trim().length < 2) {
                        this.errors.name = 'Name must be at least 2 characters';
                    }
                    break;
                    
                case 'email':
                    if (!this.form.email.trim()) {
                        this.errors.email = 'Email address is required';
                    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.form.email)) {
                        this.errors.email = 'Please enter a valid email address';
                    }
                    break;
                    
                case 'date':
                    if (!this.form.date) {
                        this.errors.date = 'Preferred date is required';
                    } else {
                        const selectedDate = new Date(this.form.date);
                        const today = new Date();
                        today.setHours(0, 0, 0, 0);
                        if (selectedDate < today) {
                            this.errors.date = 'Please select a future date';
                        }
                    }
                    break;
            }
        },
        
        validateForm() {
            this.validateField('name');
            this.validateField('email');
            this.validateField('date');
            return Object.keys(this.errors).every(key => !this.errors[key]);
        },
        
        async submitForm() {
            this.success = false;
            this.error = null;
            
            if (!this.validateForm()) {
                return;
            }
            
            this.submitting = true;
            
            try {
                const response = await fetch('api/booking.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        ...this.form,
                        csrf_token: document.querySelector('input[name="csrf_token"]').value
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.success = true;
                    this.form = {
                        name: '',
                        email: '',
                        date: '',
                        guests: 2,
                        package_id: '',
                        message: ''
                    };
                    lucide.createIcons();
                } else {
                    this.error = data.message || 'Something went wrong. Please try again.';
                }
            } catch (err) {
                this.error = 'Network error. Please check your connection and try again.';
            } finally {
                this.submitting = false;
                lucide.createIcons();
            }
        }
    }
}
</script>
