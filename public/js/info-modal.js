// Event listener untuk modal
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('infoModal');
    const modalTitle = modal.querySelector('.modal-title');
    const modalBody = modal.querySelector('.modal-body .terms-content');

     // Reset modal content when hidden
    modal.addEventListener('hidden.bs.modal', function() {
        modalTitle.textContent = '';
        modalBody.innerHTML = '';
    });
    
    // Menangani klik pada icon info
    document.querySelectorAll('.info-trigger').forEach(trigger => {
        trigger.addEventListener('click', function() {
            const infoId = this.getAttribute('data-info-id');
            
            // Fetch data dari server
            fetch(`/terms/info/${infoId}`)
                .then(response => response.json())
                .then(data => {
                    if (!data || !data.content) {
                        modalBody.innerHTML = '<div class="alert alert-warning">No information found.</div>';
                        return;
                    }

                    modalTitle.textContent = data.title;

                    let htmlContent = '';

                    data.content.forEach(section => {
                        // htmlContent += `
                        //     <div class="alert alert-info mb-3">
                        //         <p class="alert-heading fw-bold">${section.title}:</p>
                        //         <p class="mb-0">${section.items[0]?.item ?? '-'}</p>
                        //     </div>
                        // `;
                        htmlContent += `
                            <p class="fw-bold mb-0">${section.title}</p>
                            <p class="mb-3">${section.items[0]?.item ?? '-'}</p>
                        `;
                        if (section.example) {
                            htmlContent += `
                                <p class="fw-bold">Example:</p>
                                <p class="text-primary">${section.example}</p>
                            `;
                        }

                        if (section.items.length > 1) {
                            htmlContent += `
                                <p class="fw-bold mt-3 mb-0">Guidelines:</p>
                                <ul>
                                    ${section.items.slice(1).map(item => `<li>${item.item}</li>`).join('')}
                                </ul>
                            `;
                        }
                    });

                    modalBody.innerHTML = htmlContent;
                })

                .catch(error => {
                    console.error('Error fetching info:', error);
                    modalBody.innerHTML = '<div class="alert alert-danger">Failed to load information</div>';
                });
        });
    });
});