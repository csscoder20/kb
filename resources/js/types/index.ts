interface ThemeElements {
    themeButton: HTMLElement | null;
    body: HTMLElement;
}

interface ChatElements {
    input: HTMLTextAreaElement | null;
    sendButton: HTMLButtonElement | null;
    container: HTMLElement | null;
}

export type { ThemeElements, ChatElements };