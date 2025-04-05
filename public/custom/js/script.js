const chatInput = document.querySelector("#chat-input");
const sendButton = document.querySelector("#send-btn");
const chatContainer = document.querySelector(".chat-container");
const themeButton = document.querySelector("#theme-btn");
const deleteButton = document.querySelector("#delete-btn");

let userText = null;

const themeImgLight = "assets/img/head_diamond_compnet.svg";
const themeImgDark = "assets/img/head_diamond_compnet_dark.svg";
const userDark = "assets/img/head-robot-white.svg";
const userLight = "assets/img/head-robot.svg";

const initialInputHeight = chatInput.scrollHeight;

const toggleControls = (isChatStarted) => {
    if (isChatStarted) {
        themeButton.classList.add("d-none");
        deleteButton.classList.remove("d-none");
    } else {
        themeButton.classList.remove("d-none");
        deleteButton.classList.add("d-none");
    }
};

const loadDataFromSessionStorage = () => {
    const themeColor = localStorage.getItem("themeColor");
    document.body.classList.toggle("light-mode", themeColor === "light_mode");
    themeButton.innerText = document.body.classList.contains("light-mode") ? "dark_mode" : "light_mode";

    const defaultText = `<div class="default-text">
        <h1>MoP-GPT</h1>
        <p class="text-muted">Find, Edit, Publish.<br><span class="text-danger">All your chat history will be lost after the browser is closed.</span></p>
    </div>`;

    const allChats = sessionStorage.getItem("all-chats") || defaultText;

    const currentUserImg = document.body.classList.contains("light-mode") ? userLight : userDark;
    const updatedChats = allChats.replace(/src="assets\/img\/user_(dark|white)\.jpg"/g, `src="${currentUserImg}"`);

    chatContainer.innerHTML = updatedChats;
    chatContainer.scrollTo(0, chatContainer.scrollHeight);

    toggleControls(allChats !== defaultText);
};

const createChatElement = (content, className) => {
    const chatDiv = document.createElement("div");
    chatDiv.classList.add("chat", className);
    chatDiv.innerHTML = content;
    return chatDiv;
};

const getChatResponse = async (incomingChatDiv) => {
    const API_URL = "/search";
    try {
        const response = await fetch(`${API_URL}?keyword=${userText}`);
        const data = await response.json();

        const chatDetails = incomingChatDiv.querySelector(".chat-details");

        if (data && data.length > 0) {
            const { title, file, pdf_file } = data[0];
            chatDetails.innerHTML = `
                <div class="incomingHeader">
                    <p>${title}</p>
                    ${file ? `<a href="/storage/${file}" download><span class="material-symbols-outlined">download</span></a>` : ""}
                </div>
                ${pdf_file ? `
                    <div class="row incomingBody">
                        <iframe src="/storage/${pdf_file}#toolbar=0&view=FitH;"></iframe>
                    </div>` : ""}
            `;
        } else {
            chatDetails.innerHTML = `
                <div class="row">
                    <p>Data yang Anda cari belum ada. Silakan coba dengan kata kunci yang lain!</p>
                </div>
            `;
        }

        saveChatToSessionStorage(incomingChatDiv.outerHTML);
        setTimeout(updateThemeImage, 0);
    } catch (error) {
        console.error("Error fetching data:", error);
        const chatDetails = incomingChatDiv.querySelector(".chat-details");
        chatDetails.innerHTML = `
            <img id="theme-img" src="${themeImgDark}" alt="chatbot-img">
            <p>Oops! Terjadi kesalahan saat mengambil data.</p>
        `;

        saveChatToSessionStorage(incomingChatDiv.outerHTML);
        setTimeout(updateThemeImage, 0);
    }

    const typingAnimation = incomingChatDiv.querySelector(".typing-animation");
    if (typingAnimation) typingAnimation.remove();

    chatContainer.scrollTo(0, chatContainer.scrollHeight);
};

const showTypingAnimation = () => {
    const html = `<div class="chat-content">
        <div class="chat-details">
            <img id="theme-img" src="${themeImgDark}" alt="chatbot-img">
            <div class="typing-animation">
                <div class="typing-dot" style="--delay: 0.2s"></div>
                <div class="typing-dot" style="--delay: 0.3s"></div>
                <div class="typing-dot" style="--delay: 0.4s"></div>
            </div>
        </div>
    </div>`;

    const incomingChatDiv = createChatElement(html, "incoming");
    chatContainer.appendChild(incomingChatDiv);
    chatContainer.scrollTo(0, chatContainer.scrollHeight);
    getChatResponse(incomingChatDiv);
};

const handleOutgoingChat = () => {
    const currentUserImg = document.body.classList.contains("light-mode") ? userLight : userDark;
    userText = chatInput.value.trim();
    if (!userText) return;

    chatInput.value = "";
    chatInput.style.height = `${initialInputHeight}px`;

    const html = `<div class="chat-content">
        <div class="chat-details">
            <p>${userText}</p>
        </div>
    </div>`;

    const outgoingChatDiv = createChatElement(html, "outgoing");
    chatContainer.querySelector(".default-text")?.remove();
    chatContainer.appendChild(outgoingChatDiv);
    chatContainer.scrollTo(0, chatContainer.scrollHeight);

    saveChatToSessionStorage(outgoingChatDiv.outerHTML);

    // Tampilkan tombol delete, sembunyikan tombol tema
    toggleControls(true);

    setTimeout(showTypingAnimation, 500);
};

const saveChatToSessionStorage = (chatHTML) => {
    let allChats = sessionStorage.getItem("all-chats") || "";
    allChats += chatHTML;
    sessionStorage.setItem("all-chats", allChats);
};

const updateThemeImage = () => {
    const themeImg = document.querySelector("#theme-img");
    if (!themeImg) return;
    themeImg.src = document.body.classList.contains("light-mode") ? themeImgLight : themeImgDark;
};

const updateAllChatImages = () => {
    const chatImages = document.querySelectorAll(".chat-details img");
    const currentUserImg = document.body.classList.contains("light-mode") ? userLight : userDark;

    chatImages.forEach((img) => {
        if (img.id === "theme-img") {
            img.src = document.body.classList.contains("light-mode") ? themeImgLight : themeImgDark;
        } else {
            img.src = currentUserImg;
        }
    });
};

themeButton.addEventListener("click", () => {
    document.body.classList.toggle("light-mode");
    localStorage.setItem("themeColor", document.body.classList.contains("light-mode") ? "light_mode" : "dark_mode");
    themeButton.innerText = document.body.classList.contains("light-mode") ? "dark_mode" : "light_mode";

    updateThemeImage();
    updateAllChatImages();
});

deleteButton.addEventListener("click", () => {
    if (confirm("Are you sure you want to delete all the chats?")) {
        localStorage.removeItem("all-chats");
        sessionStorage.removeItem("all-chats");
        loadDataFromSessionStorage();
        toggleControls(false);
    }
});

document.addEventListener("DOMContentLoaded", () => {
    const themeColor = localStorage.getItem("themeColor");
    if (themeColor === "light_mode") {
        document.body.classList.add("light-mode");
    }

    updateThemeImage();
    loadDataFromSessionStorage();
});

chatInput.addEventListener("input", () => {
    chatInput.style.height = `${initialInputHeight}px`;
    chatInput.style.height = `${chatInput.scrollHeight}px`;
});

chatInput.addEventListener("keydown", (e) => {
    if (e.key === "Enter" && !e.shiftKey && window.innerWidth > 800) {
        e.preventDefault();
        handleOutgoingChat();
    }
});

sendButton.addEventListener("click", handleOutgoingChat);
