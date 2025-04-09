const chatInput = document.querySelector("#chat-input"),
    sendButton = document.querySelector("#send-btn"),
    chatContainer = document.querySelector(".chat-container"),
    themeButton = document.querySelector("#theme-btn"),
    deleteButton = document.querySelector("#delete-btn");
let userText = null,
    config = null,
    themeImgLight, themeImgDark, userLight, userDark, logoLight, logoDark;
const initialInputHeight = chatInput.scrollHeight,
    toggleControls = e => {
        themeButton.classList.toggle("d-none", e), deleteButton.classList.toggle("d-none", !e)
    },
    updateLogoImage = () => {
        let e = document.querySelector("#logo-img");
        e && (e.src = document.body.classList.contains("light-mode") ? logoLight : logoDark)
    },
    loadDataFromSessionStorage = async () => {
        let e = localStorage.getItem("themeColor"),
            t = "light_mode" === e;
        document.body.classList.toggle("light-mode", t), themeButton.innerText = t ? "dark_mode" : "light_mode";
        try {
            let a = await fetch("/config");
            themeImgLight = (config = await a.json()).logo_light, themeImgDark = config.logo_dark, logoLight = config.logo_light, logoDark = config.logo_dark
        } catch (o) {
            console.error("Failed to fetch config:", o)
        }
        let i = config?.title,
            l = config?.description,
            n = config?.alert,
            s = `<div class="default-text"><img id="logo-img" src="${document.body.classList.contains("light-mode")?logoLight:logoDark}" alt="Logo"><h1>${i}</h1><p class="text-muted text-center">${l}<br><span class='text-warning text-small'>${n}</span></p></div>`,
            r = sessionStorage.getItem("all-chats") || s,
            c = t ? userLight : userDark,
            d = r.replace(/src="assets\/img\/user_(dark|white)\.jpg"/g, `src="${c}"`);
        chatContainer.innerHTML = d, chatContainer.scrollTo(0, chatContainer.scrollHeight), toggleControls(r !== s)
    }, createChatElement = (e, t) => {
        let a = document.createElement("div");
        return a.classList.add("chat", t), a.innerHTML = e, a
    }, getChatResponse = async e => {
        try {
            let t = await fetch("/config");
            config = await t.json()
        } catch (a) {
            console.error("Failed to fetch config:", a)
        }
        let o = config?.text_available;
        config?.pdf_unavailable;
        let i = config?.text_unavailable;
        try {
            let l = await fetch(`/search?keyword=${userText}`),
                n = await l.json(),
                s = e.querySelector(".chat-details");
            if (n && n.length > 0) {
                let r = `<div class="incomingHeader mb-2"><p> ${n.length} ${o}</p></div>`;
                n.forEach((e, t) => {
                    let {
                        title: a,
                        file: o,
                        pdf_file: i,
                        tags: l
                    } = e, s = l && l.length ? l.map(e => `<span class="badge me-1" style="background-color: ${e.color}; color: #fff;">${e.name}</span>`).join(" ") : '<span class="badge bg-secondary">Uncategorized</span>', c = i ? `<iframe src="/storage/${i}#toolbar=0&view=FitH;" width="100%" height="400px" style="border:1px solid #ccc; border-radius:6px;"></iframe>` : '<p class="text-muted">${textPDF}</p>';
                    1 === n.length ? r += `<div class="incomingItem mb-3"><div class="mb-1 d-flex justify-content-between align-items-center"><span>${a} ${s}</span><a href="/storage/${o}" download title="Download file"><span class="material-symbols-outlined">download</span></a></div><div class="pdf-preview">${c}</div></div>` : r += `<div class="incomingItem mb-3"><div class="d-flex justify-content-between align-items-center"><a href="#" class="toggle-preview text-decoration-none mb-1" data-index="${t}">${a} ${s}</a><a href="/storage/${o}" download title="Download file"><span class="material-symbols-outlined">download</span></a></div><div class="pdf-preview" id="pdf-preview-${t}" style="display:none;">${c}</div></div>`
                }), s.innerHTML = r, n.length > 1 && s.querySelectorAll(".toggle-preview").forEach(e => {
                    e.addEventListener("click", t => {
                        t.preventDefault();
                        let a = e.getAttribute("data-index"),
                            o = s.querySelector(`#pdf-preview-${a}`);
                        o.style.display = "none" === o.style.display ? "block" : "none"
                    })
                })
            } else s.innerHTML = `<div class="row"><p>${i}</p></div>`;
            saveChatToSessionStorage(e.outerHTML), setTimeout(updateThemeImage, 0)
        } catch (c) {
            console.error("Error fetching data:", c);
            let d = e.querySelector(".chat-details");
            d.innerHTML = `<img id="theme-img" src="${themeImgDark}" alt="chatbot-img"><p>Oops! Terjadi kesalahan saat mengambil data.</p>`, saveChatToSessionStorage(e.outerHTML), setTimeout(updateThemeImage, 0)
        }
        let g = e.querySelector(".typing-animation");
        g && g.remove(), chatContainer.scrollTo(0, chatContainer.scrollHeight)
    }, showTypingAnimation = () => {
        let e = document.body.classList.contains("light-mode"),
            t = e ? themeImgLight : themeImgDark,
            a = `<div class="chat-content"><div class="chat-details"><img id="theme-img" src="${t}" alt="chatbot-img"><div class="typing-animation"><div class="typing-dot" style="--delay: 0.2s"></div><div class="typing-dot" style="--delay: 0.3s"></div><div class="typing-dot" style="--delay: 0.4s"></div></div></div></div>`,
            o = createChatElement(a, "incoming");
        chatContainer.appendChild(o), chatContainer.scrollTo(0, chatContainer.scrollHeight), getChatResponse(o)
    }, handleOutgoingChat = () => {
        if (document.body.classList.contains("light-mode"), !(userText = chatInput.value.trim())) return;
        chatInput.value = "", chatInput.style.height = `${initialInputHeight}px`;
        let e = `<div class="chat-content"><div class="chat-details"><p>${userText}</p></div></div>`,
            t = createChatElement(e, "outgoing");
        chatContainer.querySelector(".default-text")?.remove(), chatContainer.appendChild(t), chatContainer.scrollTo(0, chatContainer.scrollHeight), saveChatToSessionStorage(t.outerHTML), toggleControls(!0), setTimeout(showTypingAnimation, 500)
    }, saveChatToSessionStorage = e => {
        let t = sessionStorage.getItem("all-chats") || "";
        t += e, sessionStorage.setItem("all-chats", t)
    }, updateThemeImage = () => {
        let e = document.querySelector("#theme-img");
        e && (e.src = document.body.classList.contains("light-mode") ? themeImgLight : themeImgDark)
    }, updateAllChatImages = () => {
        let e = document.querySelectorAll(".chat-details img"),
            t = document.body.classList.contains("light-mode") ? userLight : userDark;
        e.forEach(e => {
            e.src = "theme-img" === e.id ? document.body.classList.contains("light-mode") ? themeImgLight : themeImgDark : t
        })
    };
themeButton.addEventListener("click", () => {
    document.body.classList.toggle("light-mode"),
    localStorage.setItem("themeColor", document.body.classList.contains("light-mode") ? "light_mode" : "dark_mode"),
    themeButton.innerText = document.body.classList.contains("light-mode") ? "dark_mode" : "light_mode",
    updateThemeImage(), updateAllChatImages(), updateLogoImage(), updateModalTheme()
}), deleteButton.addEventListener("click", () => {
    Swal.fire({
        title: "Delete Chat?",
        text: "This will delete all your chat!",
        showCancelButton: true,
        cancelButtonColor: "#3085d6",
        confirmButtonColor: "#d33",
        confirmButtonText: "Delete",
        reverseButtons: true
    }).then(e => {
        if (e.isConfirmed) {
            localStorage.removeItem("all-chats");
            sessionStorage.removeItem("all-chats");
            loadDataFromSessionStorage();
            toggleControls(false);
        }
    });
}), document.addEventListener("DOMContentLoaded", () => {
    sessionStorage.removeItem("all-chats");
    let e = localStorage.getItem("themeColor");
    "light_mode" === e && document.body.classList.add("light-mode"), updateThemeImage(), updateLogoImage(), loadDataFromSessionStorage()
}), chatInput.addEventListener("input", () => {
    chatInput.style.height = `${initialInputHeight}px`, chatInput.style.height = `${chatInput.scrollHeight}px`
}), chatInput.addEventListener("keydown", e => {
    "Enter" === e.key && !e.shiftKey && window.innerWidth > 800 && (e.preventDefault(), handleOutgoingChat())
}), sendButton.addEventListener("click", handleOutgoingChat);