const HSThemeAppearance = {
    // inicialize the theme appearance
    init() {
        const defaultTheme = 'light';
        let theme = localStorage.getItem('hs_theme') || defaultTheme;

        if (document.querySelector('html').classList.contains('dark')) {
            this.setAppearance('dark');
        } else {
            this.setAppearance(theme);
        }

        document.addEventListener('click', (e) => {
            const element = e.target.closest('[data-hs-theme-click-value]');
            if (!element) return;

            const theme = element.getAttribute('data-hs-theme-click-value');
            this.setAppearance(theme);
        });
    },

    setAppearance(theme, saveInStore = true) {
        if (saveInStore) {
            localStorage.setItem('hs_theme', theme);
        }

        if (theme === 'dark' || (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    },
};

export default HSThemeAppearance;
