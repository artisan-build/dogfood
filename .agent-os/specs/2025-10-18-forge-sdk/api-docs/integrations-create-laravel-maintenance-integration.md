---
source: https://forge.laravel.com/docs/api-reference/integrations/create-laravel-maintenance-integration.md
fetched: 2025-10-19T14:36:29.954Z
---

# Create Laravel Maintenance integration

> Enable maintenance mode for the site.

Processing mode: <small><code>async</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi post /orgs/{organization}/servers/{server}/sites/{site}/integrations/laravel-maintenance
paths:
  path: >-
    /orgs/{organization}/servers/{server}/sites/{site}/integrations/laravel-maintenance
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
              secret:
                allOf:
                  - type:
                      - string
                      - 'null'
                    description: >-
                      The secret phrase that allows access to the application
                      while in maintenance mode.
                    example: my-secret-phrase
              status:
                allOf:
                  - description: >-
                      The HTTP status code that should be returned while in
                      maintenance mode.
                    example: 503
                    $ref: '#/components/schemas/MaintenanceModeStatusCode'
              redirect:
                allOf:
                  - type:
                      - string
                      - 'null'
                    description: >-
                      The redirect URL to which all requests should be sent
                      while in maintenance mode.
                    example: https://example.com/maintenance
            required: true
            title: EnableMaintenanceModeRequest
            refIdentifier: '#/components/schemas/EnableMaintenanceModeRequest'
            requiredProperties:
              - status
        examples:
          example:
            value:
              secret: my-secret-phrase
              status: 503
              redirect: https://example.com/maintenance
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
  schemas:
    MaintenanceModeStatusCode:
      type: integer
      enum:
        - 304
        - 307
        - 410
        - 503
      title: MaintenanceModeStatusCode

````