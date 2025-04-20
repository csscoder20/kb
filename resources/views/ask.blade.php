@extends('layouts.app')
@section('content')
<div class="px-lg-5 d-flex flex-column flex-grow-1 position-relative">
    <div class="chat-container flex-grow-1 overflow-auto"></div>
    @guest
    <div class="alert alert-warning " role="alert">
        <strong>Notice:</strong> You cannot upload, preview, download, or find any reports until you are logged in.
    </div>

    <div class="default-text">
        <img src="{{ asset('storage/' . $basicConfig['logo_light'] ?? '') }}" alt="Logo" class="mb-4"
            style="max-width: 200px; margin: 0 auto;">
        <h3 class="mb-3">{{ $basicConfig['title'] ?? 'MoP-GPT' }}</h3>
        <p>{{ $basicConfig['description'] ?? 'Your MoP Report Partner | Find, Edit, & Publish.' }}</p>
    </div>
    @endguest

    @auth
    <div class="typing-container centered">
        <div class="typing-content">
            <div class="typing-textarea">
                <textarea id="chat-input" spellcheck="false" placeholder="Search here ..." required
                    autofocus></textarea>
            </div>
            <div class="typing-controls">
                <span id="delete-btn" class="material-symbols-rounded d-none text-danger" title="Delete All"><i
                        class="bi bi-trash3-fill"></i></span>
            </div>
        </div>
    </div>
    @endauth
</div>

<style>
    span#delete-btn i {
        font-size: 20px;
    }

    .chat.outgoing .chat-details {
        text-align: right;
    }

    @media screen and (max-width: 1000px) {
        .typing-container {
            position: fixed;
            bottom: 50px;
            left: 0;
            margin-left: 0 !important;
            margin-right: 0 !important;
            width: 100% !important;
            z-index: 999;
            padding: 20px 10px 5px 10px;
            transition: all 0.3s ease;
        }
    }

    .typing-container {
        position: fixed;
        bottom: 50px;
        left: 0;
        margin-left: 25%;
        margin-right: 25%;
        width: 50%;
        z-index: 999;
        padding: 20px 10px 5px 10px;
        transition: all 0.3s ease;
    }

    .typing-container.centered {
        position: absolute;
        width: 70%;
        box-shadow: none;
        border-top: none;
        padding: 0 20px;
        margin-left: 15%;
        margin-right: 15%;
    }

    .chat-container {
        min-height: 200px;
        overflow-y: auto;
        padding-bottom: 250px;
    }

    .typing-content {
        display: flex;
        align-items: center;
        box-shadow: 0px 3px 6px 0px rgba(0, 0, 0, 0.1),
            0px 1px 3px 0px rgba(0, 0, 0, 0.08);
        padding: 20px;
        border-radius: 20px;
        background: #fff;
    }

    textarea#chat-input:focus-visible {
        border: 0 !important;
        outline: 0 !important;
    }

    textarea#chat-input {
        width: 100%;
        border: 0;
    }

    .typing-textarea {
        width: 100%;
    }

    :root {
        --text-color: #343541;
        --icon-color: #a9a9bc;
        --icon-hover-bg: #f1f1f3;
        --placeholder-color: #6c6c6c;
        --outgoing-chat-bg: #ffffff;
        --incoming-chat-bg: #f7f7f8;
        --outgoing-chat-border: #ffffff;
        --incoming-chat-border: #d9d9e3;
    }

    .typing-animation {
        display: flex;
        gap: 6px;
        padding: 12px 0;
        margin-left: 12px;
        position: relative;
    }

    .typing-dot {
        width: 7px;
        height: 7px;
        background: #666;
        border-radius: 50%;
        opacity: 0.7;
        animation: pulseAnimation 1.5s ease-in-out infinite;
    }

    .typing-dot:nth-child(2) {
        animation-delay: 0.2s;
    }

    .typing-dot:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes pulseAnimation {

        0%,
        100% {
            transform: scale(1);
            opacity: 0.7;
        }

        50% {
            transform: scale(1.3);
            opacity: 1;
        }
    }

    .chat-result {
        margin-top: 8px;
        margin-bottom: 15px;
        padding: 12px 15px;
        border-radius: 8px;
        background: rgba(0, 0, 0, 0.05);
    }

    .chat-result p {
        margin-bottom: 5px;
        color: #1a1a1a;
    }

    .incomingHeader {
        color: #666;
        font-size: 0.9em;
        margin-bottom: 8px;
        margin-left: 12px;
    }

    .default-text {
        text-align: center;
        padding: 10px 20px;
        color: #999;
        font-size: 1.1em;
    }

    .incomingItem {
        background: #fff;
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .incomingItem .bi-file-earmark-word {
        color: #2b579a;
        transition: transform 0.2s ease;
    }

    .incomingItem .bi-file-earmark-word:hover {
        transform: scale(1.1);
    }

    .pdf-preview {
        margin-top: 10px;
    }

    .pdf-preview iframe {
        transition: all 0.3s ease;
    }

    .pdf-preview iframe:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Untuk tampilan mobile */
    @media (max-width: 768px) {
        .pdf-preview iframe {
            height: 300px;
        }
    }

    footer.py-3.mt-auto {
        position: fixed;
        bottom: 0;
        height: 150px;
        width: 100%;
        background-color: #fff;
    }

    footer.py-3.mt-auto .container {
        display: flex;
        justify-content: center;
        align-items: end;
        height: 100%;
    }

    /* ajhdjasd */
    .chat-container {
        min-height: 200px;
        overflow-y: auto;
        padding-bottom: 220px;
        position: relative;
        z-index: 1;
    }

    .incomingItem {
        background: #fff;
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        z-index: 10;
        position: relative;
    }

    .typing-container {
        position: fixed;
        bottom: 50px;
        left: 0;
        margin-left: 25%;
        margin-right: 25%;
        width: 50%;
        z-index: 999;
        padding: 20px 10px 5px 10px;
        transition: all 0.3s ease;
    }

    footer.py-3.mt-auto {
        position: fixed;
        bottom: 0;
        height: 150px;
        width: 100%;
        background-color: #fff;
        z-index: 998;
    }

    .toggle-icon {
        transition: transform 0.3s ease;
        cursor: pointer;
        padding: 8px;
        margin: -8px;
    }

    .toggle-icon:hover {
        color: #0d6efd;
    }

    span#delete-btn {
        background-color: #ecf0f6;
        padding: 10px;
        width: 50px;
        height: 50px;
        border-radius: 30px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    button.swal2-cancel.swal2-styled {
        background-color: transparent;
        border: 2px solid #ecf0f6 !important;
        color: #0d0d0d;
    }
</style>

@auth
<script>
    const chatInput = document.querySelector("#chat-input"),
        chatContainer = document.querySelector(".chat-container"),
        deleteButton = document.querySelector("#delete-btn");

    let userText = null,
        config = null;

    const initialInputHeight = chatInput.scrollHeight;

    const toggleControls = e => {
        deleteButton.classList.toggle("d-none", !e);
    };

    const updateTypingContainerPosition = () => {
        const typingContainer = document.querySelector('.typing-container');
        const hasChats = chatContainer.querySelector('.chat') !== null;
        if (!hasChats) {
            typingContainer.classList.add('centered');
        } else {
            typingContainer.classList.remove('centered');
        }
    };

    const createChatElement = (content, type) => {
        let element = document.createElement("div");
        element.classList.add("chat", type);
        element.innerHTML = content;
        return element;
    };

    // Tambahkan loading state HTML
    const loadingContent = `
        <div class="default-text">
            <div class="spinner-border text-primary mb-4" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p>Loading...</p>
        </div>
    `;

    const createDefaultContent = () => {
        // Pastikan config sudah ada
        if (!config) return loadingContent;
        
        return `
            <div class="default-text">
                <img src="${config.logo_light}" alt="Logo" class="mb-4" style="max-width: 200px; margin: 0 auto;">
                <h3 class="mb-3">${config.title || 'MoP-GPT'}</h3>
                <p>${config.description || 'Your MoP Report Partner | Find, Edit, & Publish.'}</p>
            </div>
        `;
    };

    const loadDataFromSessionStorage = async () => {
        try {
            // Tampilkan loading state terlebih dahulu
            chatContainer.innerHTML = loadingContent;
            
            // Fetch config
            let response = await fetch("/config");
            config = await response.json();
            
            let savedChats = sessionStorage.getItem("all-chats") || "";
            if (!savedChats) {
                chatContainer.innerHTML = createDefaultContent();
            } else {
                chatContainer.innerHTML = savedChats;
            }
            chatContainer.scrollTo(0, chatContainer.scrollHeight);
            toggleControls(savedChats !== "");
            updateTypingContainerPosition();
        } catch (error) {
            console.error("Failed to fetch config:", error);
            chatContainer.innerHTML = `
                <div class="default-text text-danger">
                    <p>Failed to load configuration. Please refresh the page.</p>
                </div>
            `;
        }
    };

    const showTypingAnimation = () => {
        let typingHtml = `<div class="chat-content">
            <div class="chat-details">
                <div class="typing-animation">
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                </div>
            </div>
        </div>`;
        
        let typingElement = createChatElement(typingHtml, "incoming");
        chatContainer.appendChild(typingElement);
        chatContainer.scrollTo(0, chatContainer.scrollHeight);
        getChatResponse(typingElement);
    };

   const getChatResponse = async (typingElement) => {
        try {
            let response = await fetch(`/search?keyword=${userText}`);
            let data = await response.json();

            if (data && data.length > 0) {
                let resultsHtml = `<div class="incomingHeader mb-2">
                    <p>${data.length} ${config.text_available || 'results found'}</p>
                </div>`;

                // Jika hanya ada 1 hasil, langsung tampilkan preview
                if (data.length === 1) {
                    const item = data[0];
                    const pdfPath = item.pdf_file ? `/storage/${item.pdf_file}` : '';
                    const docxPath = item.file ? `/storage/${item.file}` : '';
                    
                    resultsHtml += `
                        <div class="incomingItem mb-4">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div>
                                    <span>${item.title}</span>
                                    ${item.category ? `<span class="badge bg-secondary ms-2">${item.category}</span>` : ''}
                                </div>
                                ${docxPath ? `
                                    <a href="${docxPath}" download title="Download file" class="text-success text-decoration-none">
                                        <i class="bi bi-file-earmark-word fs-5"></i>
                                    </a>
                                ` : ''}
                            </div>
                            <div class="pdf-preview">
                                ${pdfPath ? `
                                    <iframe src="${pdfPath}#toolbar=0&view=FitH" 
                                        width="100%" 
                                        height="600px" 
                                        style="border:1px solid #ccc; border-radius:6px;">
                                    </iframe>
                                ` : '<p class="text-muted">PDF preview not available</p>'}
                            </div>
                        </div>
                    `;
                } else {
                    // Jika lebih dari 1 hasil, tampilkan daftar expandable
                    resultsHtml += `<div class="list-group mb-4">`;
                    data.forEach((item, index) => {
                        const pdfPath = item.pdf_file ? `/storage/${item.pdf_file}` : '';
                        const docxPath = item.file ? `/storage/${item.file}` : '';
                        
                        resultsHtml += `
                            <div class="list-group-item preview-item">
                                <a href="#" class="preview-link text-decoration-none text-muted fw-normal" data-index="${index}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="fw-normal">${item.title}</span>
                                            ${item.category ? `<span class="badge bg-secondary ms-2">${item.category}</span>` : ''}
                                        </div>
                                        <div class="d-flex align-items-center">
                                            ${docxPath ? `
                                                <a href="${docxPath}" download title="Download file" class="text-success text-decoration-none me-3">
                                                    <i class="bi bi-file-earmark-word fs-5"></i>
                                                </a>
                                            ` : ''}
                                            <i class="bi bi-chevron-down toggle-icon"></i>
                                        </div>
                                    </div>
                                </a>
                                <div class="preview-content mt-3" style="display: none;">
                                    <div class="pdf-preview">
                                        ${pdfPath ? `
                                            <iframe src="${pdfPath}#toolbar=0&view=FitH" 
                                                width="100%" 
                                                height="600px" 
                                                style="border:1px solid #ccc; border-radius:6px;">
                                            </iframe>
                                        ` : '<p class="text-muted">PDF preview not available</p>'}
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    resultsHtml += `</div>`;
                }

                typingElement.querySelector(".chat-details").innerHTML = resultsHtml;

                // Event listener untuk preview links dan toggle icons
                if (data.length > 1) {
                    typingElement.querySelectorAll('.preview-link, .toggle-icon').forEach(element => {
                        element.addEventListener('click', (e) => {
                            e.preventDefault();
                            e.stopPropagation(); // Mencegah event bubbling
                            
                            // Tentukan preview item berdasarkan elemen yang diklik
                            const previewItem = element.classList.contains('toggle-icon') 
                                ? element.closest('.preview-item')
                                : element.closest('.preview-item');
                            
                            const previewContent = previewItem.querySelector('.preview-content');
                            const toggleIcon = previewItem.querySelector('.toggle-icon');
                            
                            // Tutup semua preview yang sedang terbuka kecuali yang diklik
                            typingElement.querySelectorAll('.preview-content').forEach(content => {
                                if (content !== previewContent && content.style.display === 'block') {
                                    content.style.display = 'none';
                                    content.closest('.preview-item').querySelector('.toggle-icon')
                                        .classList.replace('bi-chevron-up', 'bi-chevron-down');
                                    content.closest('.preview-item').classList.remove('active');
                                }
                            });

                            // Toggle preview yang diklik
                            if (previewContent.style.display === 'none') {
                                previewContent.style.display = 'block';
                                toggleIcon.classList.replace('bi-chevron-down', 'bi-chevron-up');
                                previewItem.classList.add('active');
                                
                                // Scroll ke preview dengan offset
                                setTimeout(() => {
                                    const offset = previewItem.offsetTop - 100;
                                    window.scrollTo({
                                        top: offset,
                                        behavior: 'smooth'
                                    });
                                }, 100);
                            } else {
                                previewContent.style.display = 'none';
                                toggleIcon.classList.replace('bi-chevron-up', 'bi-chevron-down');
                                previewItem.classList.remove('active');
                            }
                        });
                    });

                    // Tambahkan style cursor pointer ke icon
                    typingElement.querySelectorAll('.toggle-icon').forEach(icon => {
                        icon.style.cursor = 'pointer';
                    });
                }

                // Fungsi scroll yang diperbarui
                const scrollToBottom = () => {
                    const lastChat = chatContainer.lastElementChild;
                    if (lastChat) {
                        const rect = lastChat.getBoundingClientRect();
                        const offset = rect.bottom + window.pageYOffset - window.innerHeight + 300; // tambah offset untuk input container
                        window.scrollTo({
                            top: offset,
                            behavior: 'smooth'
                        });
                    }
                };

                // Handle PDF iframes
                const iframes = typingElement.querySelectorAll('iframe');
                if (iframes.length > 0) {
                    Promise.all(
                        Array.from(iframes).map(iframe => 
                            new Promise(resolve => {
                                iframe.onload = () => {
                                    setTimeout(resolve, 100);
                                };
                                setTimeout(resolve, 3000); // Fallback
                            })
                        )
                    ).then(scrollToBottom);
                } else {
                    setTimeout(scrollToBottom, 100);
                }

                // Tambahkan observer untuk memantau perubahan tinggi konten
                const resizeObserver = new ResizeObserver(() => {
                    scrollToBottom();
                });
                resizeObserver.observe(typingElement);

                // Event handler untuk anchor links
                setTimeout(() => {
                    document.querySelectorAll('.incomingHeader + div a[href^="#preview-"]').forEach(link => {
                        link.addEventListener('click', function (e) {
                            e.preventDefault();
                            const target = document.querySelector(this.getAttribute('href'));
                            if (target) {
                                target.scrollIntoView({ behavior: 'smooth' });
                            }
                        });
                    });
                }, 300);

            } else {
                typingElement.querySelector(".chat-details").innerHTML = 
                    `<p>No results found for your search.</p>`;
                scrollToBottom();
            }

            saveChatToSessionStorage(typingElement.outerHTML);

        } catch (error) {
            console.error("Error fetching search results:", error);
            typingElement.querySelector(".chat-details").innerHTML = 
                `<p>Oops! We couldn’t find any data. Try adding some data first.</p>`;
            scrollToBottom();
        }
    };


    const handleOutgoingChat = () => {
        if (!(userText = chatInput.value.trim())) return;
        chatInput.value = "";
        chatInput.style.height = `${initialInputHeight}px`;
        
        let e = `<div class="chat-content">
            <div class="chat-details">
                <p>${userText}</p>
            </div>
        </div>`;
        
        let t = createChatElement(e, "outgoing");
        chatContainer.querySelector(".default-text")?.remove();
        chatContainer.appendChild(t);
        
        // Scroll ke chat yang baru ditambahkan
        const rect = t.getBoundingClientRect();
        const offset = rect.bottom + window.pageYOffset - window.innerHeight + 300;
        window.scrollTo({
            top: offset,
            behavior: 'smooth'
        });
        
        saveChatToSessionStorage(t.outerHTML);
        toggleControls(true);
        updateTypingContainerPosition();
        setTimeout(showTypingAnimation, 500);
    };

    const saveChatToSessionStorage = e => {
        let t = sessionStorage.getItem("all-chats") || "";
        t += e;
        sessionStorage.setItem("all-chats", t);
    };

    deleteButton.addEventListener("click", () => {
        Swal.fire({
            title: "Delete Chat?",
            text: "This will delete all your chat!",
            showCancelButton: true,
            cancelButtonColor: "transparent",
            confirmButtonColor: "#e02e2a",
            confirmButtonText: "Delete",
            reverseButtons: true
        }).then(e => {
            if (e.isConfirmed) {
                sessionStorage.removeItem("all-chats");
                chatContainer.innerHTML = createDefaultContent();
                toggleControls(false);
                updateTypingContainerPosition();
            }
        });
    });

    // Pastikan ini dijalankan saat DOM ready
    document.addEventListener("DOMContentLoaded", () => {
        sessionStorage.removeItem("all-chats"); // Hapus chat setiap refresh
        loadDataFromSessionStorage();
        updateTypingContainerPosition();

        const observer = new MutationObserver(updateTypingContainerPosition);
        observer.observe(chatContainer, { childList: true, subtree: true });
    });

    chatInput.addEventListener("input", () => {
        chatInput.style.height = `${initialInputHeight}px`;
        chatInput.style.height = `${chatInput.scrollHeight}px`;
    });

    chatInput.addEventListener("keydown", e => {
        if ("Enter" === e.key && !e.shiftKey && window.innerWidth > 800) {
            e.preventDefault();
            handleOutgoingChat();
        }
    });
</script>
@endauth
<script>
    window.authUser = @json(auth()->user());
</script>
@endsection