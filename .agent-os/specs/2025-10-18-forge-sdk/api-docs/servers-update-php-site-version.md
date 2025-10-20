---
source: https://forge.laravel.com/docs/api-reference/servers/update-php-site-version.md
fetched: 2025-10-19T14:40:23.101Z
---

# Update PHP site version

> Update the PHP site version for the server.

Processing mode: <small><code>async</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi put /orgs/{organization}/servers/{server}/php/site-version
paths:
  path: /orgs/{organization}/servers/{server}/php/site-version
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
              php_version:
                allOf:
                  - type: string
                    description: The PHP version to update the CLI to.
                    enum:
                      - '5.6'
                      - '7.0'
                      - '7.1'
                      - '7.2'
                      - '7.3'
                      - '7.4'
                      - '8.0'
                      - '8.1'
                      - '8.2'
                      - '8.3'
                      - '8.4'
                      - '8.5'
                    example: '7.4'
            required: true
            title: UpdatePhpCliVersionRequest
            refIdentifier: '#/components/schemas/UpdatePhpCliVersionRequest'
            requiredProperties:
              - php_version
        examples:
          example:
            value:
              php_version: '7.4'
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