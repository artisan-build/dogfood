---
source: https://forge.laravel.com/docs/api-reference/background-processes/update-background-process.md
fetched: 2025-10-19T14:34:21.512Z
---

# Update background process

> Update the supervisor configuration for a background process.

Processing mode: <small><code>async</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi put /orgs/{organization}/servers/{server}/background-processes/{backgroundProcess}
paths:
  path: >-
    /orgs/{organization}/servers/{server}/background-processes/{backgroundProcess}
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
        backgroundProcess:
          schema:
            - type: integer
              required: true
              description: The background process ID
      query: {}
      header: {}
      cookie: {}
    body:
      application/json:
        schemaArray:
          - type: object
            properties:
              name:
                allOf:
                  - type: string
                    description: The name of the background process.
              config:
                allOf:
                  - type: string
                    description: The supervisor configuration of the background process.
            required: true
            title: UpdateBackgroundProcessRequest
            refIdentifier: '#/components/schemas/UpdateBackgroundProcessRequest'
            requiredProperties:
              - name
        examples:
          example:
            value:
              name: <string>
              config: <string>
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