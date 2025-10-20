---
source: https://forge.laravel.com/docs/api-reference/recipes/get-recipe-run.md
fetched: 2025-10-19T14:38:01.026Z
---

# Get recipe run

> Show a specific run for the recipe.

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi get /orgs/{organization}/recipes/{recipe}/runs/{log}
paths:
  path: /orgs/{organization}/recipes/{recipe}/runs/{log}
  method: get
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
        log:
          schema:
            - type: integer
              required: true
              description: The log ID
      query: {}
      header: {}
      cookie: {}
    body: {}
  response:
    '200':
      application/vnd.api+json:
        schemaArray:
          - type: object
            properties:
              data:
                allOf:
                  - $ref: '#/components/schemas/RecipeLogResource'
            requiredProperties:
              - data
        examples:
          example:
            value:
              data:
                id: <string>
                type: recipeLogs
                attributes:
                  server_id: 123
                  executed_by: 123
                  recipe_id: 123
                  status: waiting
                  output: <string>
                  started_at: '2023-11-07T05:31:56Z'
                  finished_at: '2023-11-07T05:31:56Z'
                links:
                  self:
                    href: <string>
                    rel: <string>
                    describedby: <string>
                    title: <string>
                    type: <string>
                    hreflang: <string>
                    meta: {}
        description: '`RecipeLogResource`'
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
  schemas:
    Link:
      type: object
      properties:
        href:
          type: string
          format: uri
        rel:
          type: string
        describedby:
          type: string
        title:
          type: string
        type:
          type: string
        hreflang:
          anyOf:
            - type: string
            - type: array
              items:
                type: string
        meta:
          type: object
      required:
        - href
      title: Link
    RecipeLogResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
          enum:
            - recipeLogs
        attributes:
          type: object
          properties:
            server_id:
              type: integer
            executed_by:
              type:
                - integer
                - 'null'
            recipe_id:
              type: integer
            status:
              $ref: '#/components/schemas/RecipeStatus'
            output:
              type:
                - string
                - 'null'
            started_at:
              type:
                - string
                - 'null'
              format: date-time
            finished_at:
              type:
                - string
                - 'null'
              format: date-time
          required:
            - server_id
            - executed_by
            - recipe_id
            - status
            - output
            - started_at
            - finished_at
        links:
          type: object
          properties:
            self:
              $ref: '#/components/schemas/Link'
          required:
            - self
      required:
        - id
        - type
        - links
      title: RecipeLogResource
    RecipeStatus:
      type: string
      enum:
        - waiting
        - running
        - finished
        - failed
      title: RecipeStatus

````