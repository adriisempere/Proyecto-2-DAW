document.addEventListener('DOMContentLoaded', () => {
    const themeToggle = document.getElementById('theme-toggle');
    const htmlElement = document.documentElement;
    const icon = themeToggle?.querySelector('i');

    // Check for saved user preference, if any, on load of the website
    const savedTheme = localStorage.getItem('theme');
    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

    if (savedTheme === 'dark' || (!savedTheme && systemPrefersDark)) {
        htmlElement.setAttribute('data-theme', 'dark');
        if (icon) {
            icon.classList.replace('bi-moon-fill', 'bi-sun-fill');
        }
    }

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const currentTheme = htmlElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            htmlElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);

            // Update icon
            if (icon) {
                if (newTheme === 'dark') {
                    icon.classList.replace('bi-moon-fill', 'bi-sun-fill');
                } else {
                    icon.classList.replace('bi-sun-fill', 'bi-moon-fill');
                }
            }
        });
    }
});
