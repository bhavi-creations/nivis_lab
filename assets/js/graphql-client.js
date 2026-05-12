// Frontend GraphQL client for the nivis_lab project
// Save this file as assets/js/graphql-client.js

const GraphQLClient = {
  endpoint: './graphql-api.php',
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

      const json = await response.json();

      if (json.errors) {
        const message = json.errors
          .map((err) => err.message || JSON.stringify(err))
          .join(', ');
        throw new Error(`GraphQL Error: ${message}`);
      }

      if (!response.ok) {
        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
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
window.loadProducts = GraphQLClient.getProducts.bind(GraphQLClient);
window.getProduct = GraphQLClient.getProduct.bind(GraphQLClient);
window.getRelatedProducts = GraphQLClient.getRelatedProducts.bind(GraphQLClient);
window.addToCart = GraphQLClient.addToCart.bind(GraphQLClient);
window.getCart = GraphQLClient.getCart.bind(GraphQLClient);
