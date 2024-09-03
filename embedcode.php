<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<div class="container mt-12">
    <h1 class="text-center mb-4">SEO Meta Title Generator</h1>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <form id="meta-title-form" class="card p-4 shadow-sm">
                <div class="mb-3">
                    <label for="keyword" class="form-label">Keywords:</label>
                    <span style="display: block; font-size: 14px; padding-bottom: 10px">
                        Enter the main keywords you want to focus on for your page.
                    </span>
                    <input type="text" id="keyword" name="keyword" class="form-control" placeholder="Easy Weeknight Dinner Recipes" required>
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">Type:</label>
                    <span style="display: block; font-size: 14px; padding-bottom: 10px">
                        Select the type of page you're creating
                    </span>
                    <select id="type" name="type" class="form-select">
                        <option value="Blog">Blog</option>
                        <option value="Landing Page">Landing Page</option>
                    </select>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary" id="submit_btn_api">Generate Meta Title</button>
                </div>
            </form>

            <div id="meta-title-result" class="mt-4 p-3 bg-light rounded shadow-sm" style="display: none;">
                <!-- The generated meta title will be displayed here -->
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS (Optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- JavaScript for handling form submission and processing JSON response -->
<script>
    document.getElementById('meta-title-form').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        // Get form data
        const formData = new FormData(this);

        // Send POST request using fetch
        fetch('https://api.sonnynabong.dev/generate_meta_title.php', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json()) // Parse JSON response
        .then(data => {
            // Extract the meta title from the JSON response
            const metaTitle = data.meta_title;

            // Update the meta-title-result div with the meta title and show it
            const resultDiv = document.getElementById('meta-title-result');
            resultDiv.innerHTML = `<strong>Here is your new SEO Meta Title:</strong><br>${metaTitle}`;
            resultDiv.style.display = 'block';
            resultDiv.style.backgroundColor = '#e3f2fd'; // Light blue background
            resultDiv.style.border = '1px solid #0d6efd'; // Border color

            // Disable and hide the submit button
            const submitButton = document.getElementById('submit_btn_api');
            submitButton.disabled = true;
            submitButton.style.display = 'none';
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('meta-title-result').textContent = 'Error processing the response.';
        });
    });
</script>
