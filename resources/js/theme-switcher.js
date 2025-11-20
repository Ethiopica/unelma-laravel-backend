// Theme Switcher - Direct DOM manipulation for reliable theme switching
(function() {
    'use strict';

    // Theme color mappings
    const themes = {
        dark: {
            'bg-[#102B27]': '#102B27',
            'bg-[#27413C]': '#27413C',
            'text-[#75D7CB]': '#75D7CB',
            'text-[#75D7CB]/60': 'rgba(117, 215, 203, 0.6)',
            'text-[#75D7CB]/70': 'rgba(117, 215, 203, 0.7)',
            'text-[#75D7CB]/80': 'rgba(117, 215, 203, 0.8)',
            'text-[#75D7CB]/50': 'rgba(117, 215, 203, 0.5)',
            'border-[#75D7CB]/20': 'rgba(117, 215, 203, 0.2)',
            'border-[#75D7CB]/30': 'rgba(117, 215, 203, 0.3)',
            'border-[#75D7CB]/40': 'rgba(117, 215, 203, 0.4)',
            'bg-[#75D7CB]/20': 'rgba(117, 215, 203, 0.2)',
            'bg-[#75D7CB]/30': 'rgba(117, 215, 203, 0.3)',
            'bg-[#27413C]/50': 'rgba(39, 65, 60, 0.5)',
            'bg-[#27413C]/80': 'rgba(39, 65, 60, 0.8)',
        },
        light: {
            'bg-[#102B27]': '#F9FDFC',
            'bg-[#27413C]': '#FFFFFF',
            'text-[#75D7CB]': '#102B27',
            'text-[#75D7CB]/60': 'rgba(16, 43, 39, 0.7)',
            'text-[#75D7CB]/70': 'rgba(16, 43, 39, 0.7)',
            'text-[#75D7CB]/80': 'rgba(16, 43, 39, 0.8)',
            'text-[#75D7CB]/50': 'rgba(16, 43, 39, 0.5)',
            'border-[#75D7CB]/20': 'rgba(39, 65, 60, 0.15)',
            'border-[#75D7CB]/30': 'rgba(39, 65, 60, 0.25)',
            'border-[#75D7CB]/40': 'rgba(39, 65, 60, 0.35)',
            'bg-[#75D7CB]/20': 'rgba(39, 65, 60, 0.1)',
            'bg-[#75D7CB]/30': 'rgba(39, 65, 60, 0.15)',
            'bg-[#27413C]/50': 'rgba(39, 65, 60, 0.05)',
            'bg-[#27413C]/80': 'rgba(39, 65, 60, 0.1)',
        }
    };

    function applyTheme(theme) {
        const themeColors = themes[theme];
        if (!themeColors) return;

        // Update data-theme attribute
        document.documentElement.setAttribute('data-theme', theme);
        if (document.body) {
            document.body.setAttribute('data-theme', theme);
        }

        // Apply styles directly to elements
        Object.keys(themeColors).forEach(className => {
            const escapedClass = className.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            const elements = document.querySelectorAll(`.${escapedClass}`);
            
            elements.forEach(element => {
                if (className.startsWith('bg-')) {
                    const color = themeColors[className];
                    element.style.backgroundColor = color;
                } else if (className.startsWith('text-')) {
                    const color = themeColors[className];
                    element.style.color = color;
                } else if (className.startsWith('border-')) {
                    const color = themeColors[className];
                    element.style.borderColor = color;
                }
            });
        });

        // Also update computed styles for elements with inline styles
        const allElements = document.querySelectorAll('*');
        allElements.forEach(element => {
            const classList = Array.from(element.classList);
            classList.forEach(cls => {
                if (themeColors[cls]) {
                    if (cls.startsWith('bg-')) {
                        element.style.setProperty('background-color', themeColors[cls], 'important');
                    } else if (cls.startsWith('text-')) {
                        element.style.setProperty('color', themeColors[cls], 'important');
                    } else if (cls.startsWith('border-')) {
                        element.style.setProperty('border-color', themeColors[cls], 'important');
                    }
                }
            });
        });

        console.log('Theme applied:', theme);
    }

    // Initialize theme
    function initTheme() {
        const savedTheme = localStorage.getItem('theme') || 'dark';
        applyTheme(savedTheme);
    }

    // Expose theme switching function
    window.switchTheme = function(theme) {
        applyTheme(theme);
        localStorage.setItem('theme', theme);
    };

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initTheme);
    } else {
        initTheme();
    }

    // Watch for theme changes
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'data-theme') {
                const theme = document.documentElement.getAttribute('data-theme');
                if (theme) {
                    applyTheme(theme);
                }
            }
        });
    });

    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['data-theme']
    });
})();





