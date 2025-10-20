---
source: https://forge.laravel.com/docs/api-reference/recipes/create-recipe-run.md
fetched: 2025-10-19T14:37:58.494Z
---

# Create recipe run

> Run a given recipe on a list of servers.

Processing mode: <small><code>async</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi post /orgs/{organization}/recipes/{recipe}/runs
paths:
  path: /orgs/{organization}/recipes/{recipe}/runs
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
        recipe:
          schema:
            - type: integer
              required: true
              description: The recipe ID
      query: {}
      header: {}
      cookie: {}
    body:
      application/json:
        schemaArray:
          - type: object
            properties:
              email:
                allOf:
                  - type: boolean
                    description: >-
                      Whether to send an email notification when the recipe has
                      completed.
                    example: true
              servers:
                allOf:
                  - type: array
                    description: The servers on which to run the recipe on.
                    example:
                      - 1
                      - 2
                      - 3
                    items:
                      type: integer
            required: true
            title: RunRecipeRequest
            refIdentifier: '#/components/schemas/RunRecipeRequest'
            requiredProperties:
              - servers
        examples:
          example:
            value:
              email: true
              servers:
                - 1
                - 2
                - 3
  response:
    '202':
      _mintlify/placeholder:
        schemaArray:
          - type: any
            description: Recipe run accepted
        examples: {}
        description: Recipe run accepted
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