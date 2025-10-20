---
source: https://forge.laravel.com/docs/api-reference/databases/update-database-user.md
fetched: 2025-10-19T14:34:59.178Z
---

# Update database user

> Update a database user on the server.

Processing mode: <small><code>async</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi put /orgs/{organization}/servers/{server}/database/users/{databaseUser}
paths:
  path: /orgs/{organization}/servers/{server}/database/users/{databaseUser}
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
        databaseUser:
          schema:
            - type: integer
              required: true
              description: The database user ID
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
                  - type:
                      - string
                      - 'null'
                    description: The password for the database user.
                    example: password
                    maxLength: 255
              database_ids:
                allOf:
                  - type: array
                    description: The IDs of the databases to assign the user to.
                    example:
                      - 1
                      - 2
                      - 3
                    items:
                      type: integer
            title: UpdateDatabaseUserRequest
            refIdentifier: '#/components/schemas/UpdateDatabaseUserRequest'
        examples:
          example:
            value:
              password: password
              database_ids:
                - 1
                - 2
                - 3
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