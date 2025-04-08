// Classe pour gérer l'interface utilisateur
class UI {
    constructor() {
        this.chatButton = document.getElementById('chat-button');
        this.chatModal = document.getElementById('chat-modal');
        this.closeChat = document.getElementById('close-chat');
        this.chatMessages = document.getElementById('chat-messages');
        this.userInput = document.getElementById('user-input');
        this.sendMessage = document.getElementById('send-message');
        this.productsGrid = document.getElementById('products-grid');
        
        this.initializeEventListeners();
    }

    initializeEventListeners() {
        // Chat events
        this.chatButton.addEventListener('click', () => this.toggleChat(true));
        this.closeChat.addEventListener('click', () => this.toggleChat(false));
        this.sendMessage.addEventListener('click', () => this.handleSendMessage());
        this.userInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') this.handleSendMessage();
        });
    }

    toggleChat(show) {
        this.chatModal.classList.toggle('active', show);
    }

    async handleSendMessage() {
        const message = this.userInput.value.trim();
        if (message === '') return;

        this.addMessage(message, 'user');
        this.userInput.value = '';

        try {
            const response = await this.sendToGemini(message);
            this.addMessage(response, 'bot');
        } catch (error) {
            console.error('Erreur:', error);
            this.addMessage('Désolé, une erreur est survenue.', 'bot');
        }
    }

    async sendToGemini(message) {


        const response = await fetch('chat.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ message })
        });

        const data = await response.json();
        console.log(data);
        return data.response;
    }

    addMessage(text, sender) {
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('message', `${sender}-message`);
        messageDiv.textContent = text;
        this.chatMessages.appendChild(messageDiv);
        this.chatMessages.scrollTop = this.chatMessages.scrollHeight;
    }

    async loadProducts() {
        try {
            const response = await fetch('get_products.php');
            const data = await response.json();
            
            if (data.success) {
                this.displayProducts(data.products);
            } else {
                throw new Error(data.error || 'Erreur lors du chargement des produits');
            }
        } catch (error) {
            console.error('Erreur lors du chargement des produits:', error);
            this.productsGrid.innerHTML = '<p class="error">Erreur lors du chargement des produits</p>';
        }
    }

    displayProducts(products) {
        this.productsGrid.innerHTML = '';
        products.forEach(product => {
            const productCard = this.createProductCard(product);
            this.productsGrid.appendChild(productCard);
        });
    }

    createProductCard(product) {
        const card = document.createElement('div');
        card.classList.add('product-card');
        card.innerHTML = `
            <img src="${product.image}" alt="${product.name}" onerror="this.src='assets/images/placeholder.jpg'">
            <h3>${product.name}</h3>
            <p class="description">${product.description || ''}</p>
            <p class="price">${product.price.toFixed(2)} €</p>
            <button class="add-to-cart" data-id="${product.id}">Ajouter au panier</button>
        `;
        return card;
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    const ui = new UI();
    ui.loadProducts();
}); 