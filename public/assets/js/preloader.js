// Preloader functionality for the application
class Preloader {
    constructor() {
        this.preloaderElement = null;
        this.mainContentElement = null;
        this.minDisplayTime = 1000; // Minimum time to show preloader (1 second)
        this.fadeOutDuration = 300; // Fade out animation duration
    }

    // Initialize preloader with custom options
    init(options = {}) {
        this.minDisplayTime = options.minDisplayTime || this.minDisplayTime;
        this.fadeOutDuration = options.fadeOutDuration || this.fadeOutDuration;

        // Create preloader HTML if it doesn't exist
        this.createPreloaderHTML();

        // Hide preloader when page loads
        window.addEventListener('load', () => {
            this.hidePreloader();
        });
    }

    // Create the preloader HTML structure
    createPreloaderHTML() {
        // Check if preloader already exists
        if (document.getElementById('preloader')) {
            this.preloaderElement = document.getElementById('preloader');
            this.mainContentElement = document.getElementById('main-content');
            return;
        }

        // Create preloader element
        const preloader = document.createElement('div');
        preloader.id = 'preloader';
        preloader.className = 'fixed inset-0 bg-white z-50 flex items-center justify-center';

        preloader.innerHTML = `
            <div class="relative">
                <!-- Outer rotating circle (clockwise) -->
                <div class="w-24 h-24 border-4 border-gray-300 rounded-full animate-spin">
                    <!-- Inner rotating circle (counter-clockwise) -->
                    <div class="absolute top-2 left-2 w-16 h-16 border-4 border-transparent border-t-gray-400 border-r-gray-400 rounded-full animate-spin" style="animation-direction: reverse; animation-duration: 1.5s;"></div>
                </div>
                <!-- Gray motorcycle icon in center -->
                <div class="absolute inset-0 flex items-center justify-center">
                    <i class="fas fa-motorcycle text-3xl text-gray-400"></i>
                </div>
            </div>
        `;

        // Insert preloader at the beginning of body
        document.body.insertBefore(preloader, document.body.firstChild);

        // Wrap existing content in main-content div if not already wrapped
        const existingContent = document.querySelector('.container');
        if (existingContent && !document.getElementById('main-content')) {
            const mainContent = document.createElement('div');
            mainContent.id = 'main-content';
            mainContent.className = 'opacity-0 transition-opacity duration-500';
            existingContent.parentNode.insertBefore(mainContent, existingContent);
            mainContent.appendChild(existingContent);
        }

        this.preloaderElement = preloader;
        this.mainContentElement = document.getElementById('main-content');
    }

    // Hide the preloader with animation
    hidePreloader() {
        setTimeout(() => {
            if (this.preloaderElement) {
                this.preloaderElement.style.opacity = '0';
                setTimeout(() => {
                    this.preloaderElement.style.display = 'none';
                    if (this.mainContentElement) {
                        this.mainContentElement.classList.remove('opacity-0');
                        this.mainContentElement.classList.add('opacity-100');
                    }
                }, this.fadeOutDuration);
            }
        }, this.minDisplayTime);
    }

    // Show preloader manually (useful for AJAX requests)
    showPreloader() {
        if (!this.preloaderElement) {
            this.createPreloaderHTML();
        }

        // Show preloader
        this.preloaderElement.style.display = 'flex';
        this.preloaderElement.style.opacity = '1';

        // Hide main content
        if (this.mainContentElement) {
            this.mainContentElement.classList.remove('opacity-100');
            this.mainContentElement.classList.add('opacity-0');
        }
    }

    // Hide preloader manually
    hidePreloaderManual() {
        this.hidePreloader();
    }
}

// Auto-initialize preloader when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    const preloader = new Preloader();
    preloader.init();

    // Make preloader available globally
    window.Preloader = preloader;
});
