<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nivis Labs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">
        <h3>EverShop లో ఉన్న కేటగిరీలు:</h3>
        <hr>
        <ul class="list-group" id="categoriesMenu" style="max-width: 400px;">
            <li class="list-group-item text-muted">Loading categories...</li>
        </ul>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetchAllCategories();
        });

        async function fetchAllCategories() {
            const menu = document.getElementById('categoriesMenu');

            try {
                // మనం క్రియేట్ చేసిన fetch_categories.php కి రిక్వెస్ట్ పంపుతున్నాం
                const response = await fetch('fetch_categories.php');
                const result = await response.json();

                if (result.errors) {
                    console.error(result.errors);
                    menu.innerHTML = `<li class="list-group-item list-group-item-danger">GraphQL Error</li>`;
                    return;
                }

                // EverShop నుండి వచ్చిన కేటగిరీల లిస్ట్
                const categories = result.data?.categories?.items || [];

                menu.innerHTML = ''; // పాత 'Loading...' టెక్స్ట్ ని తీసేయడానికి

                if (categories.length === 0) {
                    menu.innerHTML = `<li class="list-group-item">No categories found in EverShop.</li>`;
                    return;
                }

                // ప్రతి కేటగిరీని లిస్ట్ లో యాడ్ చేయడం
                categories.forEach(category => {
                    menu.innerHTML += `
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>${category.name}</strong> 
                    <span class="badge bg-secondary text-light">Slug: ${category.url_key}</span>
                </li>
            `;
                });

            } catch (error) {
                console.error('Error:', error);
                menu.innerHTML = `<li class="list-group-item list-group-item-danger">Error: ${error.message}</li>`;
            }
        }
    </script>

</body>

</html>