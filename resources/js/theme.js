const HSThemeAppearance = {
    // inicialize the theme appearance
    init() {
        const defaultTheme = 'light';
        const theme = localStorage.getItem('hs_theme') || defaultTheme;

        this.setAppearance(theme); // aplica o que estiver salvo ou o default

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

        console.log('Aplicando tema:', theme); // Log para depuração

        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
            console.log('Classe dark adicionada ao elemento <html>'); // Log para depuração
        } else {
            document.documentElement.classList.remove('dark');
            console.log('Classe dark removida do elemento <html>'); // Log para depuração
        }
    },
};

export default HSThemeAppearance;
