---
source: https://forge.laravel.com/docs/api-reference/recipes/delete-a-recipe-share.md
fetched: 2025-10-19T14:38:08.148Z
---

# Delete a recipe share

> Unshare a recipe with a team.

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi delete /orgs/{organization}/teams/{team}/recipes/{recipe}
paths:
  path: /orgs/{organization}/teams/{team}/recipes/{recipe}
  method: delete
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
        team:
          schema:
            - type: integer
              required: true
              description: The team ID
        recipe:
          schema:
            - type: integer
              required: true
              description: The recipe ID
      query: {}
      header: {}
      cookie: {}
    body: {}
  response:
    '204':
      _mintlify/placeholder:
        schemaArray:
          - type: any
            description: |-
              Recipe unshared successfully



              No content
        examples: {}
        description: |-
          Recipe unshared successfully



          No content
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
  schemas: {}

````