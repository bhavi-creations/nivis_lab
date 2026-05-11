// Frontend GraphQL client for the nivis_lab project
// Save this file as assets/js/graphql-client.js

const GraphQLClient = {
  endpoint: './graphql-endpoint.php',
  headers: {
    'Content-Type': 'application/json',
  },

  async request(query, variables = {}) {
    const response = await fetch(this.endpoint, {
      method: 'POST',
      headers: this.headers,
      body: JSON.stringify({ query, variables }),
      credentials: 'include',
    });

    const json = await response.json();

    if (!response.ok || json.errors) {
      const message = json.errors
        ? json.errors.map((err) => err.message).join(', ')
        : response.statusText;
      throw new Error(`GraphQL request failed: ${message}`);
    }

    return json.data;
  },

  async getProducts() {
    const query = `
      query GetProducts {
        products {
          id
          name
          price
          imageUrl
          description
        }
      }
    `;

    return this.request(query);
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

// Global functions for easy access
window.loadProducts = GraphQLClient.getProducts.bind(GraphQLClient);
window.addToCart = GraphQLClient.addToCart.bind(GraphQLClient);
window.getCart = GraphQLClient.getCart.bind(GraphQLClient);
