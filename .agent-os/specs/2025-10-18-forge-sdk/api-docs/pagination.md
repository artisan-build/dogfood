---
source: https://forge.laravel.com/docs/api-reference/pagination.md
fetched: 2025-10-19T14:34:06.222Z
---

# Pagination

> Learn how to handle pagination in the Laravel Forge API.

All API endpoints that return multiple items support pagination. By default, `30` items are returned per page. You can specify the number of items to return per page by passing the `per_page` parameter. To navigate between pages, use the `page` parameter.

```http  theme={null}
GET /orgs/coinfly/servers?page=2&per_page=15
```
