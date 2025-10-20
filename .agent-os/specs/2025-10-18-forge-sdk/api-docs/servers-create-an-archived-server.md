---
source: https://forge.laravel.com/docs/api-reference/servers/create-an-archived-server.md
fetched: 2025-10-19T14:39:39.580Z
---

# Create an archived server

> Archive a server.

Processing mode: <small><code>async</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi post /orgs/{organization}/servers/archives
paths:
  path: /orgs/{organization}/servers/archives
  method: post
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
      query: {}
      header: {}
      cookie: {}
    body:
      application/json:
        schemaArray:
          - type: object
            properties:
              server_id:
                allOf:
                  - type: integer
            required: true
            title: ArchiveRequest
            refIdentifier: '#/components/schemas/ArchiveRequest'
            requiredProperties:
              - server_id
        examples:
          example:
            value:
              server_id: 123
  response:
    '202': {}
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
    '422':
      application/json:
        schemaArray:
          - type: object
            properties:
              message:
                allOf:
                  - type: string
                    description: Errors overview.
              errors:
                allOf:
                  - type: object
                    description: >-
                      A detailed description of each field that failed
                      validation.
                    additionalProperties:
                      type: array
                      items:
                        type: string
            requiredProperties:
              - message
              - errors
        examples:
          example:
            value:
              message: <string>
              errors: {}
        description: Validation error
  deprecated: false
  type: path
  xMint:
    metadata:
      noindex: true
components:
  schemas: {}

````