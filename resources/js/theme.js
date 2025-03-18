const HSThemeAppearance = {
    init() {
        const defaultTheme = 'dark';
        const theme = localStorage.getItem('hs_theme') || defaultTheme;
        this.setAppearance(theme);

        document.addEventListener('DOMContentLoaded', () => {
            const buttons = document.querySelectorAll('[data-hs-theme-click-value]');
            buttons.forEach(button => {
                button.addEventListener('click', () => {
                    const theme = button.getAttribute('data-hs-theme-click-value');
                    this.setAppearance(theme);
                });
            });
        });
    },

    setAppearance(theme) {
        localStorage.setItem('hs_theme', theme);

        if (theme === 'auto') {
            if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        } else if (theme === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        window.dispatchEvent(new CustomEvent('on-hs-appearance-change', { detail: theme }));
    }
};

export default HSThemeAppearance;
