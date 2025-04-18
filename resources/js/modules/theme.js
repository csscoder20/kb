// Gunakan konstanta untuk nilai yang sering digunakan
const THEME_CONSTANTS = {
    LIGHT: 'light_mode',
    DARK: 'dark_mode',
    STORAGE_KEY: 'themeColor'
};

export function initializeTheme() {
    const elements = {
        themeButton: document.querySelector("#theme-btn"),
        body: document.body
    };

    if (elements.themeButton) {
        elements.themeButton.addEventListener("click", () => handleThemeToggle(elements));
    }

    loadInitialTheme(elements);
}

function handleThemeToggle(elements) {
    const { body, themeButton } = elements;
    const isLightMode = body.classList.toggle("light-mode");
    
    localStorage.setItem(THEME_CONSTANTS.STORAGE_KEY, 
        isLightMode ? THEME_CONSTANTS.LIGHT : THEME_CONSTANTS.DARK
    );
    
    updateThemeUI(isLightMode, themeButton);
}

function loadInitialTheme(elements) {
    const savedTheme = localStorage.getItem(THEME_CONSTANTS.STORAGE_KEY);
    if (savedTheme === THEME_CONSTANTS.LIGHT) {
        elements.body.classList.add("light-mode");
    }
    updateThemeImage();
    updateLogoImage();
}
