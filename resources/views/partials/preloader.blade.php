<!-- PRELOADER COMPONENT -->
<div id="preloader" style="display: none;">
    <div class="preloader-content">
        <div class="preloader-wrapper">
            <div class="loader-ring"></div>
            <div class="loader-ring-outer"></div>
            @if(isset($setting) && $setting->path_image)
                <img src="{{ Storage::url($setting->path_image) }}" alt="Logo" class="logo-loading">
            @else
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="logo-loading">
            @endif
        </div>
        <div class="loading-text">
            <span class="dot"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>
    </div>
</div>

<style>
    #preloader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.9);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 9999999;
        transition: opacity 0.5s ease;
    }

    .preloader-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .preloader-wrapper {
        position: relative;
        width: 120px;
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
    }

    .logo-loading {
        width: 70px;
        height: 70px;
        object-fit: contain;
        z-index: 10;
        filter: drop-shadow(0 0 10px rgba(16, 185, 129, 0.3));
    }

    .loader-ring {
        position: absolute;
        width: 100px;
        height: 100px;
        border: 4px solid transparent;
        border-top: 4px solid #10b981;
        border-radius: 50%;
        animation: spin 1s cubic-bezier(0.5, 0, 0.5, 1) infinite;
        z-index: 5;
    }

    .loader-ring-outer {
        position: absolute;
        width: 120px;
        height: 120px;
        border: 2px solid rgba(255, 255, 255, 0.05);
        border-radius: 50%;
        z-index: 1;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .loading-text {
        display: flex;
        gap: 5px;
    }

    .dot {
        width: 8px;
        height: 8px;
        background-color: #10b981;
        border-radius: 50%;
        animation: dotPulse 1.5s infinite ease-in-out;
    }

    .dot:nth-child(2) { animation-delay: 0.2s; }
    .dot:nth-child(3) { animation-delay: 0.4s; }

    @keyframes dotPulse {
        0%, 80%, 100% { transform: scale(0.6); opacity: 0.5; }
        40% { transform: scale(1.2); opacity: 1; }
    }
</style>

<script>
    (function() {
        const preloader = document.getElementById('preloader');
        
        // Function to show preloader
        window.showPreloader = function() {
            if (preloader) {
                preloader.style.display = 'flex';
                preloader.style.opacity = '1';
            }
        };

        // Function to hide preloader
        window.hidePreloader = function() {
            if (preloader) {
                preloader.style.opacity = '0';
                setTimeout(() => {
                    preloader.style.display = 'none';
                }, 500);
            }
        };

        // Hide on page load complete
        window.addEventListener('load', hidePreloader);

        // Hide on bfcache (back button)
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) hidePreloader();
        });

        // Intercept all link clicks for loading
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (!link) return;

            const href = link.getAttribute('href');
            const target = link.getAttribute('target');

            // Conditions for showing preloader:
            // 1. Has href
            // 2. Not a fragment link (#)
            // 3. Not javascript:
            // 4. Not tel: or mailto:
            // 5. Not opening in new tab/window
            // 6. Not a file download (heuristically)
            // 7. Not a button-like or modal trigger
            if (href && 
                href !== '#' && 
                !href.startsWith('#') &&
                !href.startsWith('javascript:') && 
                !href.startsWith('mailto:') && 
                !href.startsWith('tel:') && 
                (!target || target === '_self') &&
                !link.hasAttribute('download') &&
                !link.classList.contains('no-loader') &&
                !e.ctrlKey && !e.metaKey && !e.shiftKey && !e.altKey) {
                
                showPreloader();
            }
        });

        // Intercept form submissions
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.classList.contains('no-loader')) return;
            showPreloader();
        });

        // jQuery AJAX Global Interceptor (if jQuery exists)
        if (window.jQuery) {
            $(document).ajaxStart(function() {
                // Optional: only show for certain requests if needed
                showPreloader();
            });
            $(document).ajaxStop(function() {
                hidePreloader();
            });
            $(document).ajaxError(function() {
                hidePreloader();
            });
        }
    })();
</script>
