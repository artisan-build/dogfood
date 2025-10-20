---
source: https://forge.laravel.com/docs/api-reference/firewall-rules/delete-server-firewall-rule.md
fetched: 2025-10-19T14:35:53.813Z
---

# Delete server firewall rule

> Remove a firewall rule from the server.

Processing mode: <small><code>async</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi delete /orgs/{organization}/servers/{server}/firewall-rules/{rule}
paths:
  path: /orgs/{organization}/servers/{server}/firewall-rules/{rule}
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
        rule:
          schema:
            - type: integer
              required: true
              description: The rule ID
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