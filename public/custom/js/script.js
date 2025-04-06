const chatInput = document.querySelector("#chat-input");
const sendButton = document.querySelector("#send-btn");
const chatContainer = document.querySelector(".chat-container");
const themeButton = document.querySelector("#theme-btn");
const deleteButton = document.querySelector("#delete-btn");

let userText = null;
let config = null;

let themeImgLight, themeImgDark, userLight, userDark, logoLight, logoDark;

const initialInputHeight = chatInput.scrollHeight;

const toggleControls = (isChatStarted) => {
    themeButton.classList.toggle("d-none", isChatStarted);
    deleteButton.classList.toggle("d-none", !isChatStarted);
};

const updateLogoImage = () => {
    const logo = document.querySelector("#logo-img");
    if (logo) {
        logo.src = document.body.classList.contains("light-mode") ? logoLight : logoDark;
    }
};

const loadDataFromSessionStorage = async () => {
    const themeColor = localStorage.getItem("themeColor");
    const isLightMode = themeColor === "light_mode";
    document.body.classList.toggle("light-mode", isLightMode);
    themeButton.innerText = isLightMode ? "dark_mode" : "light_mode";

    // Fetch data basic dari backend
    try {
        const response = await fetch("/config");
        config = await response.json();
        // Set nilai gambar berdasarkan config
        themeImgLight = config.logo_light;
        themeImgDark = config.logo_dark;
        logoLight = config.logo_light;
        logoDark = config.logo_dark;

    } catch (error) {
        console.error("Failed to fetch config:", error);
    }

    // Set default fallback jika data kosong
    const forumTitle = config?.title;
    const forumDesc = config?.description;
    const textAlert = config?.alert;

    const defaultText = `<div class="default-text">
        <img id="logo-img" src="${document.body.classList.contains("light-mode") ? logoLight : logoDark}" alt="Logo">
        <h1>${forumTitle}</h1>
        <p class="text-muted">${forumDesc}<br><span class='text-warning text-small'>${textAlert}</span></p>
    </div>`;

    const allChats = sessionStorage.getItem("all-chats") || defaultText;

    // Ganti user avatar tergantung tema
    const currentUserImg = isLightMode ? userLight : userDark;
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
    try {
        const response = await fetch("/config");
        config = await response.json();
    } catch (error) {
        console.error("Failed to fetch config:", error);
    }

    // Set default fallback jika data kosong
    const textAvailableData = config?.text_available;
    const textPDF = config?.pdf_unavailable;
    const textUnavailableData = config?.text_unavailable;

    const API_URL = "/search";
    try {
        const response = await fetch(`${API_URL}?keyword=${userText}`);
        const data = await response.json();
        const chatDetails = incomingChatDiv.querySelector(".chat-details");

        if (data && data.length > 0) {
            let resultHTML = `<div class="incomingHeader mb-2"><p> ${data.length} ${textAvailableData}</p></div>`;

            data.forEach((item, index) => {
                const { title, file, pdf_file, tags } = item;
                const badges = tags && tags.length
                    ? tags.map(tag => `<span class="badge me-1" style="background-color: ${tag.color}; color: #fff;">${tag.name}</span>`).join(" ")
                    : `<span class="badge bg-secondary">Uncategorized</span>`;

                const previewHTML = pdf_file
                    ? `<iframe src="/storage/${pdf_file}#toolbar=0&view=FitH;" width="100%" height="400px" style="border:1px solid #ccc; border-radius:6px;"></iframe>`
                    : '<p class="text-muted">${textPDF}</p>';

                if (data.length === 1) {
                    resultHTML += `
                        <div class="incomingItem mb-3">
                            <div class="mb-1 d-flex justify-content-between align-items-center">
                                <span>${title} ${badges}</span>
                                <a href="/storage/${file}" download title="Download file">
                                    <span class="material-symbols-outlined">download</span>
                                </a>
                            </div>
                            <div class="pdf-preview">${previewHTML}</div>
                        </div>
                    `;
                } else {
                    resultHTML += `
                        <div class="incomingItem mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="#" class="toggle-preview text-decoration-none mb-1" data-index="${index}">${title} ${badges}</a>
                                <a href="/storage/${file}" download title="Download file">
                                    <span class="material-symbols-outlined">download</span>
                                </a>
                            </div>
                            <div class="pdf-preview" id="pdf-preview-${index}" style="display:none;">${previewHTML}</div>
                        </div>
                    `;
                }
            });

            chatDetails.innerHTML = resultHTML;

            if (data.length > 1) {
                chatDetails.querySelectorAll(".toggle-preview").forEach(anchor => {
                    anchor.addEventListener("click", (e) => {
                        e.preventDefault();
                        const index = anchor.getAttribute("data-index");
                        const preview = chatDetails.querySelector(`#pdf-preview-${index}`);
                        preview.style.display = preview.style.display === "none" ? "block" : "none";
                    });
                });
            }
        } else {
            chatDetails.innerHTML = `<div class="row"><p>${textUnavailableData}</p></div>`;
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
    const isLightMode = document.body.classList.contains("light-mode");
    const currentThemeImg = isLightMode ? themeImgLight : themeImgDark;

    const html = `<div class="chat-content">
        <div class="chat-details">
            <img id="theme-img" src="${currentThemeImg}" alt="chatbot-img">
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
        <div class="chat-details"><p>${userText}</p></div>
    </div>`;

    const outgoingChatDiv = createChatElement(html, "outgoing");
    chatContainer.querySelector(".default-text")?.remove();
    chatContainer.appendChild(outgoingChatDiv);
    chatContainer.scrollTo(0, chatContainer.scrollHeight);

    saveChatToSessionStorage(outgoingChatDiv.outerHTML);
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
    if (themeImg) {
        themeImg.src = document.body.classList.contains("light-mode") ? themeImgLight : themeImgDark;
    }
};

const updateAllChatImages = () => {
    const chatImages = document.querySelectorAll(".chat-details img");
    const currentUserImg = document.body.classList.contains("light-mode") ? userLight : userDark;

    chatImages.forEach((img) => {
        img.src = img.id === "theme-img" ? (document.body.classList.contains("light-mode") ? themeImgLight : themeImgDark) : currentUserImg;
    });
};

themeButton.addEventListener("click", () => {
    document.body.classList.toggle("light-mode");
    localStorage.setItem("themeColor", document.body.classList.contains("light-mode") ? "light_mode" : "dark_mode");
    themeButton.innerText = document.body.classList.contains("light-mode") ? "dark_mode" : "light_mode";

    updateThemeImage();
    updateAllChatImages();
    updateLogoImage();
});

deleteButton.addEventListener("click", () => {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            localStorage.removeItem("all-chats");
            sessionStorage.removeItem("all-chats");
            loadDataFromSessionStorage();
            toggleControls(false);
            Swal.fire({
                title: 'Deleted!',
                text: 'Your chats have been deleted.',
                icon: 'success',
                timer: 1000,
                showConfirmButton: false
            });
        }
    });
});

document.addEventListener("DOMContentLoaded", () => {
    // Hapus semua riwayat chat saat halaman dimuat
    sessionStorage.removeItem("all-chats");

    const themeColor = localStorage.getItem("themeColor");
    if (themeColor === "light_mode") {
        document.body.classList.add("light-mode");
    }

    updateThemeImage();
    updateLogoImage();
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
