// Frontend GraphQL client for the nivis_lab project
// Save this file as assets/js/graphql-client.js

const GraphQLClient = {
  endpoint: './graphql-api.php', // Points to backend GraphQL API
  headers: {
    'Content-Type': 'application/json',
  },

  async request(query, variables = {}) {
    try {
      const response = await fetch(this.endpoint, {
        method: 'POST',
        headers: this.headers,
        body: JSON.stringify({ query, variables }),
        credentials: 'include',
      });

      const text = await response.text();
      let json;

      try {
        json = text ? JSON.parse(text) : null;
      } catch (parseError) {
        const preview = text ? text.slice(0, 200) : 'Empty response';
        throw new Error(`Invalid JSON from server (${response.status}): ${preview}`);
      }

      if (!response.ok) {
        const message = json?.errors?.map((err) => err.message).join(', ') || response.statusText;
        throw new Error(`HTTP ${response.status}: ${message}`);
      }

      if (!json) {
        throw new Error('Empty JSON response from server');
      }

      if (json.errors) {
        const message = json.errors
          .map((err) => err.message || JSON.stringify(err))
          .join(', ');
        throw new Error(`GraphQL Error: ${message}`);
      }

      return json;
    } catch (error) {
      console.error('GraphQL Request Error:', error);
      throw new Error(`Connection error: ${error.message}`);
    }
  },

  // ===== AUTH MUTATIONS & QUERIES =====

  async login(username, password) {
    const mutation = `
      mutation Login($username: String!, $password: String!) {
        login(username: $username, password: $password) {
          success
          message
          isLoggedIn
        }
      }
    `;

    return this.request(mutation, { username, password });
  },

  async logout() {
    const mutation = `
      mutation Logout {
        logout {
          success
          message
          isLoggedIn
        }
      }
    `;

    return this.request(mutation);
  },

  async checkAuthStatus() {
    const query = `
      query CheckAuthStatus {
        authStatus {
          isLoggedIn
          username
        }
      }
    `;

    return this.request(query);
  },

  // ===== PRODUCT & CART QUERIES =====

  async getProducts(filters = {}) {
    const query = `
      query GetProducts($category: String, $concern: String, $ingredient: String, $type: String) {
        products(category: $category, concern: $concern, ingredient: $ingredient, type: $type) {
          id
          name
          price
          imageUrl
          description
          detailPage
          category
          concern
          ingredients
          type
        }
      }
    `;

    return this.request(query, filters);
  },

  async getProduct(productId) {
    const query = `
      query GetProduct($id: ID!) {
        product(id: $id) {
          id
          name
          price
          imageUrl
          description
          detailPage
          category
          concern
          ingredients
          type
          relatedContent
          relatedProductIds
        }
      }
    `;

    return this.request(query, { id: productId });
  },

  async getRelatedProducts(productId) {
    const query = `
      query GetRelatedProducts($productId: ID!) {
        relatedProducts(productId: $productId) {
          id
          name
          price
          imageUrl
          description
          detailPage
        }
      }
    `;

    return this.request(query, { productId });
  },

  async addToCart(productId, quantity) {
    const mutation = `
      mutation AddToCart($productId: ID!, $quantity: Int!) {
        addToCart(productId: $productId, quantity: $quantity) {
          success
          message
          cart {
            total
            items {
              product {
                id
                name
                price
              }
              quantity
            }
          }
        }
      }
    `;

    return this.request(mutation, { productId, quantity });
  },

  async getCart() {
    const query = `
      query GetCart {
        cart {
          total
          items {
            product {
              id
              name
              price
            }
            quantity
          }
        }
      }
    `;

    return this.request(query);
  },
};

window.GraphQLClient = GraphQLClient;
window.loadProducts = async (...args) => (await GraphQLClient.getProducts(...args)).data;
window.getProduct = async (...args) => (await GraphQLClient.getProduct(...args)).data;
window.getRelatedProducts = async (...args) => (await GraphQLClient.getRelatedProducts(...args)).data;
window.addToCart = async (productId, quantity = 1) => {
  const result = (await GraphQLClient.addToCart(productId, quantity)).data;

  if (result?.addToCart?.success && window.NivisCart) {
    try {
      const productResult = await GraphQLClient.getProduct(productId);
      const product = productResult?.data?.product;

      if (product) {
        window.NivisCart.add({
          id: product.id || productId,
          name: product.name || 'Product',
          price: Number(String(product.price || 0).replace(/,/g, '').replace(/[^0-9.]/g, '')) || 0,
          image: product.imageUrl || '',
          quantity,
        }, quantity);
      }
    } catch (error) {
      console.warn('Unable to sync GraphQL cart item locally:', error);
    }
  }

  return result;
};
window.getCart = async (...args) => (await GraphQLClient.getCart(...args)).data;

const NivisCart = {
  key: 'nivis_lab_cart',

  read() {
    try {
      const cart = JSON.parse(localStorage.getItem(this.key) || '[]');
      return Array.isArray(cart) ? cart : [];
    } catch (error) {
      return [];
    }
  },

  write(items) {
    localStorage.setItem(this.key, JSON.stringify(items));
    window.dispatchEvent(new CustomEvent('nivis-cart:updated', {
      detail: this.toCart(items),
    }));
  },

  add(item, quantity = 1) {
    if (!item || !item.name) return this.toCart();

    const id = String(item.id || item.name).trim();
    const qty = Math.max(1, Number(quantity || item.quantity || 1));
    const price = Number(item.price || 0);
    const items = this.read();
    const existing = items.find((cartItem) => cartItem.id === id);

    if (existing) {
      existing.quantity += qty;
      existing.price = price;
      if (item.image) existing.image = item.image;
    } else {
      items.push({
        id,
        name: item.name,
        price,
        image: item.image || '',
        quantity: qty,
      });
    }

    this.write(items);
    return this.toCart(items);
  },

  remove(id) {
    const itemId = String(id || '');
    const items = this.read();
    const existing = items.find((cartItem) => cartItem.id === itemId);

    if (existing) {
      existing.quantity -= 1;
      if (existing.quantity <= 0) {
        items.splice(items.indexOf(existing), 1);
      }
    }

    this.write(items);
    return this.toCart(items);
  },

  clear() {
    this.write([]);
  },

  count(items = this.read()) {
    return items.reduce((sum, item) => sum + Number(item.quantity || 0), 0);
  },

  total(items = this.read()) {
    return items.reduce((sum, item) => sum + Number(item.price || 0) * Number(item.quantity || 0), 0);
  },

  toCart(items = this.read()) {
    return {
      items,
      total: this.total(items),
      count: this.count(items),
    };
  },

  fromCard(card) {
    if (!card) return null;

    const nameEl = card.querySelector('.product-name, h6, [data-product-name]');
    const priceEl = card.querySelector('.product-price, [data-product-price]');
    const imgEl = card.querySelector('.product-img-wrap img.img-primary, .product-img-wrap img, img');
    const name = card.dataset.productName || nameEl?.textContent?.trim() || 'Product';
    const parsePrice = (value) => Number(String(value || '').replace(/,/g, '').replace(/[^0-9.]/g, '')) || 0;
    const price = parsePrice(card.dataset.price) || parsePrice(card.dataset.productPrice) || parsePrice(priceEl?.textContent);
    const idSource = card.dataset.productId || card.dataset.sku || name;
    const id = String(idSource).trim().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');

    return {
      id,
      name,
      price,
      image: card.dataset.productImage || imgEl?.src || '',
      quantity: 1,
    };
  },
};

window.NivisCart = NivisCart;
