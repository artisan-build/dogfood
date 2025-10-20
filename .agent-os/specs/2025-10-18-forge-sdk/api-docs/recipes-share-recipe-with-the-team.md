---
source: https://forge.laravel.com/docs/api-reference/recipes/share-recipe-with-the-team.md
fetched: 2025-10-19T14:38:06.066Z
---

# Share recipe with the team

> Shares a recipe with a team.

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi post /orgs/{organization}/teams/{team}/recipes
paths:
  path: /orgs/{organization}/teams/{team}/recipes
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
        team:
          schema:
            - type: integer
              required: true
              description: The team ID
      query: {}
      header: {}
      cookie: {}
    body:
      application/json:
        schemaArray:
          - type: object
            properties:
              recipe_id:
                allOf:
                  - type: integer
                    description: The ID of the recipe to share with the team.
                    example: 3
            required: true
            title: ShareRecipeRequest
            refIdentifier: '#/components/schemas/ShareRecipeRequest'
            requiredProperties:
              - recipe_id
        examples:
          example:
            value:
              recipe_id: 3
  response:
    '201':
      application/vnd.api+json:
        schemaArray:
          - type: object
            properties:
              data:
                allOf:
                  - $ref: '#/components/schemas/RecipeResource'
            requiredProperties:
              - data
        examples:
          example:
            value:
              data:
                id: <string>
                type: recipes
                attributes:
                  name: Install Security tooling
                  script: apt-get install -y my-custom-tooling
                  user: root
                  created_at: '2025-07-29T09:00:00Z'
                  updated_at: '2025-07-30T09:00:00Z'
                links:
                  self:
                    href: <string>
                    rel: <string>
                    describedby: <string>
                    title: <string>
                    type: <string>
                    hreflang: <string>
                    meta: {}
        description: |-
          Recipe shared successfully



          `RecipeResource`
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
    RecipeResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
          enum:
            - recipes
        attributes:
          type: object
          properties:
            name:
              type: string
              description: The name of the Recipe.
              examples:
                - Install Security tooling
            script:
              type: string
              description: The script that should be executed.
              examples:
                - apt-get install -y my-custom-tooling
            user:
              type: string
              description: The user that the Recipe should be executed as.
              examples:
                - root
            created_at:
              type: string
              format: date-time
              description: The date the Recipe was created.
              examples:
                - '2025-07-29T09:00:00Z'
            updated_at:
              type: string
              format: date-time
              description: The date the Recipe was last updated.
              examples:
                - '2025-07-30T09:00:00Z'
          required:
            - name
            - script
            - user
            - created_at
            - updated_at
        links:
          type: object
          properties:
            self:
              description: A link to the resource itself
              $ref: '#/components/schemas/Link'
          required:
            - self
      required:
        - id
        - type
        - links
      title: RecipeResource

````