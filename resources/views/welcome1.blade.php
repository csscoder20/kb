<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>MoP GPT | Your MoP Report Partner</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Segoe UI:wght@400;500;600&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", sans-serif;
        }

        :root {
            --text-color: #FFFFFF;
            --icon-color: #ACACBE;
            --icon-hover-bg: #5b5e71;
            --placeholder-color: #e2e8f0;
            --outgoing-chat-bg: #020617;
            --incoming-chat-bg: #0f172a;
            --outgoing-chat-border: #343541;
            --incoming-chat-border: #444654;
        }

        .light-mode {
            --text-color: #343541;
            --icon-color: #a9a9bc;
            --icon-hover-bg: #f1f1f3;
            --placeholder-color: #6c6c6c;
            --outgoing-chat-bg: #FFFFFF;
            --incoming-chat-bg: #F7F7F8;
            --outgoing-chat-border: #FFFFFF;
            --incoming-chat-border: #D9D9E3;
        }

        body {
            background: var(--outgoing-chat-bg);
        }

        /* Chats container styling */
        .chat-container {
            overflow-y: auto;
            max-height: 100vh;
            padding-bottom: 150px;
        }

        :where(.chat-container, textarea)::-webkit-scrollbar {
            width: 6px;
        }

        :where(.chat-container, textarea)::-webkit-scrollbar-track {
            background: var(--incoming-chat-bg);
            border-radius: 25px;
        }

        :where(.chat-container, textarea)::-webkit-scrollbar-thumb {
            background: var(--icon-color);
            border-radius: 25px;
        }

        .default-text {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            height: 70vh;
            padding: 0 10px;
            text-align: center;
            color: var(--text-color);
        }

        .default-text h1 {
            font-size: 3.3rem;
        }

        .default-text p {
            margin-top: 10px;
            font-size: 1.1rem;
        }

        .chat-container .chat {
            padding: 15px 10px;
            display: flex;
            justify-content: center;
            color: var(--text-color);
        }

        .chat-container .chat.outgoing {
            width: 50%;
            margin: 0 25%;
            background: var(--outgoing-chat-bg);
            /* border: 1px solid var(--outgoing-chat-border); */
        }

        .chat-container .chat.incoming {
            width: 50%;
            margin: 0 25%;
            /* background: var(--incoming-chat-bg); */
            /* border: 1px solid var(--incoming-chat-border); */
        }

        .chat-container .chat.incoming .chat-content {
            background: var(--incoming-chat-bg);
            padding: 15px;
            border-radius: 10px;
        }

        .chat .chat-content {
            display: flex;
            max-width: 1200px;
            width: 100%;
            align-items: flex-start;
            justify-content: space-between;
        }

        span.material-symbols-rounded {
            user-select: none;
            cursor: pointer;
        }

        .chat .chat-content span {
            cursor: pointer;
            line-height: 1.5;
        }

        .chat:hover .chat-content:not(:has(.typing-animation), :has(.error)) span {
            visibility: visible;
        }

        .chat .chat-details {
            display: flex;
            width: 100% !important;
        }

        .chat .chat-details img {
            width: 35px;
            height: 35px;
            align-self: flex-start;
            object-fit: cover;
            border-radius: 2px;
        }

        .chat .chat-details p {
            white-space: pre-wrap;
            font-size: 1rem;
            padding: 0 50px 0 25px;
            color: var(--text-color);
            word-break: break-word;
            width: 100% !important;
            line-height: 1.5;
        }

        .chat .chat-details p.error {
            color: #e55865;
        }

        .chat .typing-animation {
            padding-left: 25px;
            display: inline-flex;
        }

        .typing-animation .typing-dot {
            height: 7px;
            width: 7px;
            border-radius: 50%;
            margin: 0 3px;
            opacity: 0.7;
            background: var(--text-color);
            animation: animateDots 1.5s var(--delay) ease-in-out infinite;
        }

        .typing-animation .typing-dot:first-child {
            margin-left: 0;
        }

        @keyframes animateDots {

            0%,
            44% {
                transform: translateY(0px);
            }

            28% {
                opacity: 0.4;
                transform: translateY(-6px);
            }

            44% {
                opacity: 0.2;
            }
        }

        /* Typing container styling */
        .typing-container {
            position: fixed;
            bottom: 0;
            width: 100%;
            display: flex;
            padding: 20px 10px;
            justify-content: center;
            background: var(--outgoing-chat-bg);
            border-top: 1px solid var(--incoming-chat-border);
        }

        .typing-container .typing-content {
            display: flex;
            max-width: 950px;
            width: 100%;
            align-items: flex-end;
        }

        .typing-container .typing-textarea {
            width: 100%;
            display: flex;
            position: relative;
        }


        .typing-textarea textarea {
            resize: none;
            height: 55px;
            /* padding: 10px 40px 10px 10px; */
            width: 100%;
            border: none;
            padding: 15px 45px 15px 20px;
            color: var(--text-color);
            font-size: 1rem;
            border-radius: 4px;
            max-height: 250px;
            overflow-y: auto;
            background: var(--incoming-chat-bg);
            outline: 1px solid var(--incoming-chat-bg);
        }

        .typing-textarea textarea::placeholder {
            color: var(--placeholder-color);
        }

        .typing-content span {
            width: 55px;
            height: 55px;
            display: flex;
            border-radius: 4px;
            font-size: 1.35rem;
            align-items: center;
            justify-content: center;
            color: var(--icon-color);
        }

        .typing-textarea span {
            position: absolute;
            right: 0;
            bottom: 0;
            visibility: hidden;
        }

        .typing-textarea textarea:valid~span {
            visibility: visible;
        }

        .typing-controls {
            display: flex;
        }

        .typing-controls span {
            margin-left: 7px;
            font-size: 1.4rem;
            background: var(--incoming-chat-bg);
            /* outline: 1px solid var(--incoming-chat-border); */
        }

        .typing-controls span:hover {
            background: var(--icon-hover-bg);
        }

        /* Reponsive Media Query */
        @media screen and (max-width: 600px) {
            .default-text h1 {
                font-size: 2.3rem;
            }

            :where(.default-text p, textarea, .chat p) {
                font-size: 0.95rem !important;
            }

            .chat-container .chat {
                padding: 15px 10px;
            }

            .chat-container .chat img {
                height: 32px;
                width: 32px;
            }

            .chat-container .chat p {
                padding: 0 20px;
            }

            .chat .chat-content:not(:has(.typing-animation), :has(.error)) span {
                visibility: visible;
            }

            .typing-container {
                padding: 15px 10px;
            }

            .typing-content span {
                height: 45px;
                width: 45px;
                margin-left: 5px;
            }

            span.material-symbols-rounded {
                font-size: 1.25rem !important;
            }
        }

        .chat-details iframe {
            width: 100% !important;
            /* max-width: 800px; */
            height: 100vh;
            border: none;
            margin: 15px auto 0 auto !important;
            display: block;
        }
    </style>
</head>

<div class="chat-container"></div>

<!-- Typing container -->
<div class="typing-container">
    <div class="typing-content">
        <div class="typing-textarea">
            <textarea id="chat-input" spellcheck="false" placeholder="Search here ..." required autofocus></textarea>
            <span id="send-btn" class="material-symbols-rounded">send</span>
        </div>
        <div class="typing-controls">
            <span id="theme-btn" class="material-symbols-rounded">light_mode</span>
            <span id="delete-btn" class="material-symbols-rounded">delete</span>
        </div>
    </div>

    <script>
        const chatInput = document.querySelector("#chat-input");
        const sendButton = document.querySelector("#send-btn");
        const chatContainer = document.querySelector(".chat-container");
        const themeButton = document.querySelector("#theme-btn");
        const deleteButton = document.querySelector("#delete-btn");
    
        let userText = null;
    
        const loadDataFromLocalstorage = () => {
            const themeColor = localStorage.getItem("themeColor");
            document.body.classList.toggle("light-mode", themeColor === "light_mode");
            themeButton.innerText = document.body.classList.contains("light-mode") ? "dark_mode" : "light_mode";
    
            const defaultText = `<div class="default-text">
                <h1>MoP-GPT</h1>
                <p>Find, Edit, Publish.<br> Your chat history will be displayed here.</p>
            </div>`;
    
            chatContainer.innerHTML = localStorage.getItem("all-chats") || defaultText;
            chatContainer.scrollTo(0, chatContainer.scrollHeight);
        }
    
        const createChatElement = (content, className) => {
            const chatDiv = document.createElement("div");
            chatDiv.classList.add("chat", className);
            chatDiv.innerHTML = content;
            return chatDiv;
        }
    
        const getChatResponse = async (incomingChatDiv) => {
        const API_URL = "/search"; // Endpoint Laravel
        try {
        const response = await fetch(`${API_URL}?keyword=${userText}`);
        const data = await response.json();
        
        if (data && data.length > 0) {
        const { title, description, file, pdf_file } = data[0];
        
        const chatDetails = incomingChatDiv.querySelector(".chat-details");
        const pElement = document.createElement("p");
        pElement.innerHTML = `<span>${title}</span>`;
        chatDetails.appendChild(pElement);
        
        // Jika file adalah PDF, tampilkan di iframe
        if (pdf_file) {
        const fileUrl = `/storage/${pdf_file}#toolbar=0&view=FitH;`;
        const pdfPreview = document.createElement("iframe");
        pdfPreview.src = fileUrl;
        pdfPreview.style.width = "100%";
        pdfPreview.style.height = "100vh";
        pdfPreview.style.border = "none";
        pElement.appendChild(pdfPreview);
        }
        } else {
        const errorText = document.createElement("p");
        errorText.textContent = "Tidak ada hasil yang ditemukan.";
        incomingChatDiv.querySelector(".chat-details").appendChild(errorText);
        }
        } catch (error) {
        const errorText = document.createElement("p");
        errorText.textContent = "Oops! Terjadi kesalahan saat mengambil data.";
        incomingChatDiv.querySelector(".chat-details").appendChild(errorText);
        }
        
        incomingChatDiv.querySelector(".typing-animation").remove();
        chatContainer.scrollTo(0, chatContainer.scrollHeight);
        };
        
        const showTypingAnimation = () => {
            const html = `<div class="chat-content">
                <div class="chat-details">
                    <img src="https://www.barcodescanner.de/media/38/be/04/1686304229/blogbeitragbildchatgpt.png" alt="chatbot-img">
                    <div class="typing-animation">
                        <div class="typing-dot" style="--delay: 0.2s"></div>
                        <div class="typing-dot" style="--delay: 0.3s"></div>
                        <div class="typing-dot" style="--delay: 0.4s"></div>
                    </div>
                </div>
                <span onclick="copyResponse(this)" class="material-symbols-rounded">content_copy</span>
            </div>`;
    
            const incomingChatDiv = createChatElement(html, "incoming");
            chatContainer.appendChild(incomingChatDiv);
            chatContainer.scrollTo(0, chatContainer.scrollHeight);
            getChatResponse(incomingChatDiv);
        }
    
        const handleOutgoingChat = () => {
            userText = chatInput.value.trim();
            if (!userText) return;
    
            chatInput.value = "";
            chatInput.style.height = `${initialInputHeight}px`;
    
            const html = `<div class="chat-content">
                <div class="chat-details">
                    <img src="https://img.freepik.com/free-vector/chatbot-chat-message-vectorart_78370-4104.jpg" alt="user-img">
                    <p>${userText}</p>
                </div>
            </div>`;
    
            const outgoingChatDiv = createChatElement(html, "outgoing");
            chatContainer.querySelector(".default-text")?.remove();
            chatContainer.appendChild(outgoingChatDiv);
            chatContainer.scrollTo(0, chatContainer.scrollHeight);
            setTimeout(showTypingAnimation, 500);
        }
    
        deleteButton.addEventListener("click", () => {
            if (confirm("Are you sure you want to delete all the chats?")) {
                localStorage.removeItem("all-chats");
                loadDataFromLocalstorage();
            }
        });
    
        themeButton.addEventListener("click", () => {
            document.body.classList.toggle("light-mode");
            localStorage.setItem("themeColor", themeButton.innerText);
            themeButton.innerText = document.body.classList.contains("light-mode") ? "dark_mode" : "light_mode";
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
    
        loadDataFromLocalstorage();
        sendButton.addEventListener("click", handleOutgoingChat);
    </script>
    </body>

</html>