document.addEventListener('DOMContentLoaded', () => {
    // Éléments du DOM
    const chatButton = document.getElementById('chat-button');
    const chatModal = document.getElementById('chat-modal');
    const closeChat = document.getElementById('close-chat');
    const chatMessages = document.getElementById('chat-messages');
    const userInput = document.getElementById('user-input');
    const sendMessage = document.getElementById('send-message');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const productsGrid = document.getElementById('products-grid');

    // Gestion du chat
    if (chatButton) {
        chatButton.addEventListener('click', () => {
            chatModal.classList.add('active');
        });

        closeChat.addEventListener('click', () => {
            chatModal.classList.remove('active');
        });

        async function sendUserMessage() {
            const message = userInput.value.trim();
            if (message === '') return;

            addMessage(message, 'user');
            userInput.value = '';

            try {
                const response = await fetch('chat.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ message: message })
                });

                const data = await response.json();
                
                // Afficher la réponse du bot
                if (data.success) {
                    addMessage(data.response, 'bot');
                } else {
                    addMessage('Erreur: ' + data.response, 'error');
                }

                // Afficher les informations de débogage dans la console
                if (data.debug) {
                    console.log('Debug Info:', data.debug);
                }
            } catch (error) {
                console.error('Erreur:', error);
                addMessage('Désolé, une erreur est survenue lors de la communication avec le serveur.', 'error');
            }
        }

        function addMessage(text, type) {
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('message');
            messageDiv.classList.add(type + '-message');
            messageDiv.textContent = text;
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        sendMessage.addEventListener('click', sendUserMessage);
        userInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                sendUserMessage();
            }
        });
    }

    // Gestion des filtres de produits
    if (filterButtons) {
        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Mettre à jour les boutons actifs
                filterButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');

                // Charger les produits filtrés
                const category = button.dataset.category;
                loadProducts(category);
            });
        });
    }

    // Charger les produits
    async function loadProducts(category = 'all') {
        try {
            const url = category === 'all' ? 'get_products.php' : `get_products.php?category=${category}`;
            const response = await fetch(url);
            const products = await response.json();

            if (productsGrid) {
                productsGrid.innerHTML = ''; // Vider la grille

                products.forEach(product => {
                    const productCard = document.createElement('div');
                    productCard.classList.add('product-card');
                    productCard.innerHTML = `
                        <div class="product-image">
                            <img src="${product.image}" alt="${product.name}">
                        </div>
                        <div class="product-info">
                            <h3>${product.name}</h3>
                            <p class="description">${product.description}</p>
                            <p class="price">${product.price.toFixed(2)} €</p>
                            <button class="add-to-cart" data-id="${product.id}">
                                <i class="fas fa-shopping-cart"></i> Ajouter au panier
                            </button>
                        </div>
                    `;
                    productsGrid.appendChild(productCard);
                });
            }
        } catch (error) {
            console.error('Erreur lors du chargement des produits:', error);
        }
    }

    // Charger les produits au chargement de la page
    if (productsGrid) {
        loadProducts();
    }
}); 