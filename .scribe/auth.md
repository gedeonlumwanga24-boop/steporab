# Authenticating requests

To authenticate requests, include an **`Authorization`** header with the value **`"Bearer Bearer {YOUR_SANCTUM_TOKEN}"`**.

All authenticated endpoints are marked with a `requires authentication` badge in the documentation below.

Authentification via <b>Laravel Sanctum</b>. Obtenez un token via <code>POST /api/v1/auth/login</code> puis passez-le en header <code>Authorization: Bearer {token}</code>.
