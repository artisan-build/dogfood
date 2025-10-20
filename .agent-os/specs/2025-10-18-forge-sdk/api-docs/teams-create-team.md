---
source: https://forge.laravel.com/docs/api-reference/teams/create-team.md
fetched: 2025-10-19T14:42:55.890Z
---

# Create team

> Create a new team for the organization.

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi post /orgs/{organization}/teams
paths:
  path: /orgs/{organization}/teams
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
                    maxLength: 255
              users:
                allOf:
                  - type: array
                    items:
                      type: object
                      properties:
                        id:
                          type: integer
                        role:
                          type: object
                          properties:
                            id:
                              type: integer
                          required:
                            - id
                      required:
                        - id
              invites:
                allOf:
                  - type: array
                    items:
                      type: object
                      properties:
                        email:
                          type: string
                          format: email
                        role:
                          type: object
                          properties:
                            id:
                              type: integer
                          required:
                            - id
                      required:
                        - email
            required: true
            title: CreateTeamRequest
            refIdentifier: '#/components/schemas/CreateTeamRequest'
            requiredProperties:
              - name
        examples:
          example:
            value:
              name: <string>
              users:
                - id: 123
                  role:
                    id: 123
              invites:
                - email: jsmith@example.com
                  role:
                    id: 123
  response:
    '200':
      application/vnd.api+json:
        schemaArray:
          - type: object
            properties:
              data:
                allOf:
                  - $ref: '#/components/schemas/TeamResource'
            requiredProperties:
              - data
        examples:
          example:
            value:
              data:
                id: <string>
                type: teams
                attributes:
                  name: <string>
                  created_at: '2023-11-07T05:31:56Z'
                  updated_at: '2023-11-07T05:31:56Z'
                links:
                  self:
                    href: <string>
                    rel: <string>
                    describedby: <string>
                    title: <string>
                    type: <string>
                    hreflang: <string>
                    meta: {}
        description: '`TeamResource`'
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
    TeamResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
          enum:
            - teams
        attributes:
          type: object
          properties:
            name:
              type: string
            created_at:
              type:
                - string
                - 'null'
              format: date-time
            updated_at:
              type:
                - string
                - 'null'
              format: date-time
          required:
            - name
            - created_at
            - updated_at
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
      title: TeamResource

````