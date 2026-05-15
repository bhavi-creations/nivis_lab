const API_URL = "http://localhost:3000/graphql";

async function graphqlRequest(query, variables = {}) {

    const response = await fetch(API_URL, {

        method: "POST",

        headers: {
            "Content-Type": "application/json"
        },

        body: JSON.stringify({
            query,
            variables
        })
    });

    return await response.json();
}