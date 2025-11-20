<div x-data="{ 
    theme: localStorage.getItem('theme') || 'dark',
    init() {
        // Apply theme on init
        this.applyTheme(this.theme);
        // Watch for changes
        this.$watch('theme', (value) => {
            this.applyTheme(value);
        });
    },
    applyTheme(themeValue) {
        localStorage.setItem('theme', themeValue);
        document.documentElement.setAttribute('data-theme', themeValue);
        if (document.body) {
            document.body.setAttribute('data-theme', themeValue);
        }
        // Update all elements with data-theme attribute
        document.querySelectorAll('[data-theme]').forEach(el => {
            el.setAttribute('data-theme', themeValue);
        });
        // Use the global theme switcher if available
        if (window.switchTheme) {
            window.switchTheme(themeValue);
        }
        // Debug: Log theme change
        console.log('Theme changed to:', themeValue);
    },
    toggleTheme() {
        this.theme = this.theme === 'dark' ? 'light' : 'dark';
    }
}" id="theme-toggle-container">
    <div class="theme-toggle-pill" role="group" aria-label="Theme selection">
        <button
            type="button"
            class="pill-option pill-left"
            :class="{ 'is-active': theme === 'light' }"
            @click.stop="theme = 'light'"
        >
            <i class="fa-solid fa-sun"></i>
        </button>
        <button
            type="button"
            class="pill-option pill-right"
            :class="{ 'is-active': theme === 'dark' }"
            @click.stop="theme = 'dark'"
        >
            <i class="fa-solid fa-moon"></i>
        </button>
    </div>
</div>

<script>
    // Fallback vanilla JS theme toggle (in case Alpine.js hasn't loaded)
    (function() {
        const container = document.getElementById('theme-toggle-container');
        if (!container) return;
        
        const pill = container.querySelector('.theme-toggle-pill');
        if (!pill) return;
        
        // Get current theme
        function getCurrentTheme() {
            return localStorage.getItem('theme') || 'dark';
        }
        
        // Apply theme using global function
        function applyTheme(theme) {
            if (window.switchTheme) {
                window.switchTheme(theme);
            } else {
                localStorage.setItem('theme', theme);
                document.documentElement.setAttribute('data-theme', theme);
                if (document.body) {
                    document.body.setAttribute('data-theme', theme);
                }
            }
        }
        
        function updateToggleUI(theme) {
            if (!pill) return;
            const sunButton = pill.querySelector('.pill-left');
            const moonButton = pill.querySelector('.pill-right');

            if (sunButton) {
                sunButton.classList.toggle('is-active', theme === 'light');
            }

            if (moonButton) {
                moonButton.classList.toggle('is-active', theme === 'dark');
            }
        }
        
        // Initialize theme
        setTimeout(() => {
            const theme = getCurrentTheme();
            applyTheme(theme);
            updateToggleUI(theme);
        }, 100);
        
        // Add change handler as fallback
        const sunButton = pill.querySelector('.pill-left');
        const moonButton = pill.querySelector('.pill-right');

        function handleClick(button, desiredTheme) {
            button.addEventListener('click', function(e) {
                if (window.Alpine && container.__x) return;
                const currentTheme = getCurrentTheme();
                if (currentTheme === desiredTheme) return;
                applyTheme(desiredTheme);
                updateToggleUI(desiredTheme);
            });
        }

        if (sunButton) handleClick(sunButton, 'light');
        if (moonButton) handleClick(moonButton, 'dark');

        pill.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    })();
</script>

<style>
    .theme-toggle-pill {
        display: inline-flex;
        border-radius: 999px;
        border: 1px solid rgba(117, 215, 203, 0.25);
        background: rgba(17, 45, 41, 0.6);
        overflow: hidden;
        width: 100%;
        max-width: 140px;
    }

    .theme-toggle-pill .pill-option {
        flex: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.35rem 0.6rem;
        font-size: 0.8rem;
        color: rgba(117, 215, 203, 0.75);
        transition: all 180ms ease;
        border: none;
        background: transparent;
    }

    .theme-toggle-pill .pill-option i {
        pointer-events: none;
    }

    .theme-toggle-pill .pill-option.is-active {
        background: rgba(117, 215, 203, 0.2);
        color: #0B1F1C;
        font-weight: 600;
    }

    .theme-toggle-pill .pill-option.pill-left {
        border-right: 1px solid rgba(117, 215, 203, 0.25);
    }

    html[data-theme="light"] .theme-toggle-pill {
        border-color: rgba(16, 43, 39, 0.2);
        background: rgba(232, 245, 243, 0.85);
    }

    html[data-theme="light"] .theme-toggle-pill .pill-option {
        color: rgba(16, 43, 39, 0.55);
    }

    html[data-theme="light"] .theme-toggle-pill .pill-option.is-active {
        background: rgba(16, 43, 39, 0.1);
        color: #102B27;
    }
</style>

