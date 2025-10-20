---
source: https://forge.laravel.com/docs/api-reference/databases/delete-database-user.md
fetched: 2025-10-19T14:35:01.761Z
---

# Delete database user

> Remove a database user from the server.

Processing mode: <small><code>async</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi delete /orgs/{organization}/servers/{server}/database/users/{databaseUser}
paths:
  path: /orgs/{organization}/servers/{server}/database/users/{databaseUser}
  method: delete
  servers:
    - url: https://forge.laravel.com/api
  request:
    security:
      - title: oauth2
        parameters:
          query: {}
          header:
            Authorization:
              type: oauth2
          cookie: {}
    parameters:
      path:
        organization:
          schema:
            - type: string
              required: true
              description: The organization slug
        server:
          schema:
            - type: integer
              required: true
              description: The server ID
        databaseUser:
          schema:
            - type: integer
              required: true
              description: The database user ID
      query: {}
      header: {}
      cookie: {}
    body: {}
  response:
    '202':
      _mintlify/placeholder:
        schemaArray:
          - type: any
            description: Accepted
        examples: {}
        description: Accepted
    '403':
      application/json:
        schemaArray:
          - type: object
            properties:
              message:
                allOf:
                  - type: string
                    description: Error overview.
            requiredProperties:
              - message
        examples:
          example:
            value:
              message: <string>
        description: Authorization error
    '404':
      application/json:
        schemaArray:
          - type: object
            properties:
              message:
                allOf:
                  - type: string
                    description: Error overview.
            requiredProperties:
              - message
        examples:
          example:
            value:
              message: <string>
        description: Not found
  deprecated: false
  type: path
  xMint:
    metadata:
      noindex: true
components:
  schemas: {}

````