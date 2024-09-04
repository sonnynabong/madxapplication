# Meta Title Generator API Documentation,  Brief Overview, and Challenges

<p>This API generates SEO-optimized meta titles using OpenAI's GPT-4o model based on provided keywords and content types.</p>
<p>Brief Overfiew - code uses PHP since I'm more used to PHP and to save time I used the one I'm most familiar with. Used Javascript to post the request from webflow to the endpoint. Theres a 403 and origin and allowed referrer in the config to turn on some basic security and so that bots won't spam the endpoint.</p>
<p>Challenges - Initial familiarity with WebFlow and OpenAI Security tokens after that I encountered CORS erorr when requesting from the webflow app to the endpoint with was fixed with Access Control Parameters in the endpoint code.</p>

## Endpoint

POST /path/to/generate_meta_title.php

## Authentication

<p>This API uses referer and origin-based authentication. Requests must come from an allowed referrer or origin as configured in the server.</p>
<p>Turn basic 403 security on in the config if you dont want anyone except the allowed domain can request data from the endpoint</p>

## Request Headers

- `Content-Type: application/x-www-form-urlencoded`
- `Referer` or `Origin` header must be set and match the allowed values

## Request Parameters

| Parameter | Type   | Required | Description                                |
|-----------|--------|----------|--------------------------------------------|
| keyword   | string | Yes      | The main keyword for the meta title        |
| type      | string | No       | The content type (default: "Blog")         |

## Response

The API responds with a JSON object containing the generated meta title.

### Success Response

```json
{
  "meta_title": "Generated SEO-optimized meta title"
}
```

### Error Responses

1. Missing keyword:
```json
{
  "meta_title": "Keyword is required."
}
```

2. Invalid request method:
```json
{
  "meta_title": "Invalid request method."
}
```

3. Access denied (403 Forbidden):
```
Access denied.
```

## Example Usage

```javascript
fetch('https://your-api-endpoint.com/path/to/script.php', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/x-www-form-urlencoded',
  },
  body: new URLSearchParams({
    'keyword': 'digital marketing',
    'type': 'Blog Post'
  })
})
.then(response => response.json())
.then(data => console.log(data.meta_title))
.catch(error => console.error('Error:', error));
```

## Notes

- The API uses OpenAI's GPT-4o model to generate meta titles.
- All requests are logged with origin, referer, and POST data for monitoring purposes.
- CORS is enabled for the allowed origin.
- The API has a character limit for generated titles (approximately 60 tokens).

## Error Handling

- If the OpenAI API call fails, the response will include the error message.
- If the OpenAI API doesn't return the expected data structure, a default message is returned.

For any issues or questions, please contact the API administrator.
