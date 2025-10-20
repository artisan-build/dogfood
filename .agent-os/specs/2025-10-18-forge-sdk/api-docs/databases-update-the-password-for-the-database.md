---
source: https://forge.laravel.com/docs/api-reference/databases/update-the-password-for-the-database.md
fetched: 2025-10-19T14:35:03.870Z
---

# Update the password for the database

> Update the password for the database on the server.
It will only update the password Forge thinks is the password, it will not change the password on the server itself.
This should only be used if you have changed the password on the server itself and want to update Forge.

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi put /orgs/{organization}/servers/{server}/database/password
paths:
  path: /orgs/{organization}/servers/{server}/database/password
  method: put
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
      query: {}
      header: {}
      cookie: {}
    body:
      application/json:
        schemaArray:
          - type: object
            properties:
              password:
                allOf:
                  - type: string
                    maxLength: 255
            required: true
            title: UpdateDatabasePasswordRequest
            refIdentifier: '#/components/schemas/UpdateDatabasePasswordRequest'
            requiredProperties:
              - password
        examples:
          example:
            value:
              password: <string>
  response:
    '204':
      _mintlify/placeholder:
        schemaArray:
          - type: any
            description: No content
        examples: {}
        description: No content
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