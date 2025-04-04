const chatInput = document.querySelector("#chat-input");
const sendButton = document.querySelector("#send-btn");
const chatContainer = document.querySelector(".chat-container");
const themeButton = document.querySelector("#theme-btn");
const deleteButton = document.querySelector("#delete-btn");

let userText = null;
const userName = "User    "; // Ganti dengan nama pengguna yang sesuai
const themeImgLight = "assets/img/head_diamond_compnet.svg";
const themeImgDark = "assets/img/head_diamond_compnet_dark.svg";

const userDark = "assets/img/light-user-img.svg";
const userLight = "assets/img/dark-user-img.svg";

const loadDataFromLocalstorage = () => {
    const themeColor = localStorage.getItem("themeColor");
    document.body.classList.toggle("light-mode", themeColor === "light_mode");
    themeButton.innerText = document.body.classList.contains("light-mode") ? "dark_mode" : "light_mode";

    const defaultText = `<div class="default-text">
                <h1>MoP-GPT</h1>
                <p>Find, Edit, Publish.<br><span>All your chat history will be lost after the page is reloaded.</span></p>
            </div>`;

    // Ambil semua chat dari localStorage
    const allChats = localStorage.getItem("all-chats") || defaultText;

    // Ganti gambar pengguna sesuai tema
    const currentUserImg = document.body.classList.contains("light-mode") ? userLight : userDark;
    const updatedChats = allChats.replace(/src="assets\/img\/user_(dark|white)\.jpg"/g, `src="${currentUserImg}"`);

    chatContainer.innerHTML = updatedChats;
    chatContainer.scrollTo(0, chatContainer.scrollHeight);
};

const createChatElement = (content, className) => {
    const chatDiv = document.createElement("div");
    chatDiv.classList.add("chat", className);
    chatDiv.innerHTML = content;
    return chatDiv;
};

const getChatResponse = async (incomingChatDiv) => {
    const API_URL = "/search"; // Endpoint Laravel
    try {
        const response = await fetch(`${API_URL}?keyword=${userText}`);
        const data = await response.json();

        const chatDetails = incomingChatDiv.querySelector(".chat-details");

        // Tentukan gambar berdasarkan mode
        const currentThemeImg = document.body.classList.contains("light-mode") ? themeImgLight : themeImgDark;

        if (data && data.length > 0) {
            const { title, file, pdf_file } = data[0];

            chatDetails.innerHTML = `
                <img id="theme-img" src="${currentThemeImg}" alt="chatbot-img">
                <p>
                    <span style="text-align:left;">${title}</span>
                    ${pdf_file ? `<iframe src="/storage/${pdf_file}#toolbar=0&view=FitH;"></iframe>` : ""}
                </p>
                ${file ? `<a href="/storage/${file}" download><span class="material-symbols-outlined">download</span></a>` : ""}
            `;
        } else {
            chatDetails.innerHTML = `
                <img id="theme-img" src="${currentThemeImg}" alt="chatbot-img">
                <p>Data yang Anda cari belum ada. Silakan coba dengan kata kunci yang lain!</p>
            `;
        }

        // Simpan chat incoming ke localStorage
        saveChatToLocalStorage(incomingChatDiv.outerHTML);

        setTimeout(updateThemeImage, 0);
    } catch (error) {
        console.error("Error fetching data:", error);
        const currentThemeImg = document.body.classList.contains("light-mode") ? themeImgLight : themeImgDark;
        chatDetails.innerHTML = `
            <img id="theme-img" src="${currentThemeImg}" alt="chatbot-img">
            <p>Oops! Terjadi kesalahan saat mengambil data.</p>
        `;

        // Simpan chat incoming ke localStorage meskipun terjadi kesalahan
        saveChatToLocalStorage(incomingChatDiv.outerHTML);

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
    // Tentukan gambar berdasarkan mode
    const currentUserImg = document.body.classList.contains("light-mode") ? userLight : userDark;

    userText = chatInput.value.trim();
    if (!userText) return;

    chatInput.value = "";
    chatInput.style.height = `${initialInputHeight}px`;

    const html = `<div class="chat-content">
        <div class="chat-details">
            <img src="${currentUserImg}" alt="user-img">
            <p><strong>${userName}</strong><br> <span>${userText}</span></p>
        </div>
    </div>`;

    const outgoingChatDiv = createChatElement(html, "outgoing");
    chatContainer.querySelector(".default-text")?.remove();
    chatContainer.appendChild(outgoingChatDiv);
    chatContainer.scrollTo(0, chatContainer.scrollHeight);

    // Simpan chat ke localStorage
    saveChatToLocalStorage(outgoingChatDiv.outerHTML);
    
    setTimeout(showTypingAnimation, 500);
};

const saveChatToLocalStorage = (chatHTML) => {
    let allChats = localStorage.getItem("all-chats") || "";
    allChats += chatHTML; // Tambahkan chat baru
    localStorage.setItem("all-chats", allChats); // Simpan kembali ke localStorage
};

const updateAllChatImages = () => {
    const chatImages = document.querySelectorAll(".chat-details img");
    const currentUserImg = document.body.classList.contains("light-mode") ? userLight : userDark;

    chatImages.forEach((img) => {
        if (img.id === "theme-img") {
            const currentThemeImg = document.body.classList.contains("light-mode") ? themeImgLight : themeImgDark;
            img.src = currentThemeImg; // Perbarui src gambar chatbot sesuai tema
        } else {
            img.src = currentUserImg; // Perbarui src gambar pengguna sesuai tema
        }
    });
};

deleteButton.addEventListener("click", () => {
    if (confirm("Are you sure you want to delete all the chats?")) {
        localStorage.removeItem("all-chats");
        loadDataFromLocalstorage();
    }
});

const updateThemeImage = () => {
    const themeImg = document.querySelector("#theme-img");
    if (!themeImg) return;

    const isLightMode = document.body.classList.contains("light-mode");
    themeImg.src = isLightMode ? themeImgLight : themeImgDark;
};

themeButton.addEventListener("click", () => {
    document.body.classList.toggle("light-mode");
    localStorage.setItem("themeColor", document.body.classList.contains("light-mode") ? "light_mode" : "dark_mode");
    themeButton.innerText = document.body.classList.contains("light-mode") ? "dark_mode" : "light_mode";

    updateThemeImage(); // Panggil fungsi untuk memperbarui gambar
    updateAllChatImages(); // Perbarui semua gambar chat sesuai tema
});

document.addEventListener("DOMContentLoaded", () => {
    const themeColor = localStorage.getItem("themeColor");
    if (themeColor === "light_mode") {
        document.body.classList.add("light-mode");
    }
    updateThemeImage(); // Perbarui gambar sesuai tema saat halaman dimuat
    loadDataFromLocalstorage(); // Load data dari localStorage saat halaman dimuat
});

const initialInputHeight = chatInput.scrollHeight;

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