---
source: https://forge.laravel.com/docs/api-reference/integrations/create-laravel-reverb-integration.md
fetched: 2025-10-19T14:36:11.914Z
---

# Create Laravel Reverb integration

> 

Processing mode: <small><code>async</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi post /orgs/{organization}/servers/{server}/sites/{site}/integrations/reverb
paths:
  path: /orgs/{organization}/servers/{server}/sites/{site}/integrations/reverb
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
        server:
          schema:
            - type: integer
              required: true
              description: The server ID
        site:
          schema:
            - type: integer
              required: true
              description: The site ID
      query: {}
      header: {}
      cookie: {}
    body:
      application/json:
        schemaArray:
          - type: object
            properties:
              host:
                allOf:
                  - type: string
              port:
                allOf:
                  - type: string
              connections:
                allOf:
                  - type: integer
                    minimum: 1
                    maximum: 50000
            required: true
            title: EnableReverbRequest
            refIdentifier: '#/components/schemas/EnableReverbRequest'
            requiredProperties:
              - host
              - port
              - connections
        examples:
          example:
            value:
              host: <string>
              port: <string>
              connections: 25000
  response:
    '200':
      application/json:
        schemaArray:
          - type: object
            properties: {}
        examples:
          example:
            value: {}
        description: ''
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