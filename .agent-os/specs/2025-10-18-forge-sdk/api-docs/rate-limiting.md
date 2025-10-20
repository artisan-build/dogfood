---
source: https://forge.laravel.com/docs/api-reference/rate-limiting.md
fetched: 2025-10-19T14:34:08.318Z
---

# Rate Limiting

> Learn about rate limiting in the Laravel Forge API.

The Laravel Forge API implements rate limiting to ensure fair usage and protect against abuse.
The default rate limit is set to **60 requests per minute** per authenticated user.

API responses include the `X-RateLimit-Limit`, `X-RateLimit-Remaining`, and `X-RateLimit-Reset` headers to provide information about the rate limit.
