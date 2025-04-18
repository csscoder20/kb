export class ChatManager {
    constructor() {
        this.elements = {
            input: document.querySelector("#chat-input"),
            sendButton: document.querySelector("#send-btn"),
            container: document.querySelector(".chat-container")
        };
        
        this.initialInputHeight = this.elements.input?.scrollHeight || 0;
        this.initialize();
    }

    initialize() {
        try {
            this.setupEventListeners();
        } catch (error) {
            console.error('Chat initialization failed:', error);
            // Implement your error handling/reporting
        }
    }

    setupEventListeners() {
        if (this.elements.input) {
            this.elements.input.addEventListener("input", () => this.adjustInputHeight());
            this.elements.input.addEventListener("keydown", (e) => this.handleKeyPress(e));
        }

        if (this.elements.sendButton) {
            this.elements.sendButton.addEventListener("click", () => this.handleOutgoingChat());
        }
    }
}
