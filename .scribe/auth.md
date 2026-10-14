# Authenticating requests

To authenticate requests, include an **`Authorization`** header with the value **`"Bearer {YOUR_API_TOKEN}"`**.

All authenticated endpoints are marked with a `requires authentication` badge in the documentation below.

Get a token by sending your username and password to <code>POST /api/tokens</code>, then send it as <code>Authorization: Bearer {token}</code>.
