---
source: https://forge.laravel.com/docs/api-reference/servers/update-server-php-max-upload-size.md
fetched: 2025-10-19T14:40:54.615Z
---

# Update server PHP max upload size

> 

Processing mode: <small><code>async</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi put /orgs/{organization}/servers/{server}/php/max-upload-size
paths:
  path: /orgs/{organization}/servers/{server}/php/max-upload-size
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
              max_upload_size:
                allOf:
                  - type:
                      - integer
                      - 'null'
                    minimum: 0
              max_execution_time:
                allOf:
                  - type:
                      - integer
                      - 'null'
                    minimum: 0
              opcache:
                allOf:
                  - type:
                      - boolean
                      - 'null'
            title: UpdatePhpSettingsRequest
            refIdentifier: '#/components/schemas/UpdatePhpSettingsRequest'
        examples:
          example:
            value:
              max_upload_size: 1
              max_execution_time: 1
              opcache: true
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