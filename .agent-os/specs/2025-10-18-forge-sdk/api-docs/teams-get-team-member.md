---
source: https://forge.laravel.com/docs/api-reference/teams/get-team-member.md
fetched: 2025-10-19T14:43:07.730Z
---

# Get team member

> Show the team member for the team.

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi get /orgs/{organization}/teams/{team}/members/{user}
paths:
  path: /orgs/{organization}/teams/{team}/members/{user}
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
        team:
          schema:
            - type: integer
              required: true
              description: The team ID
        user:
          schema:
            - type: integer
              required: true
              description: The user ID
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
                  - $ref: '#/components/schemas/MembershipResource'
            requiredProperties:
              - data
        examples:
          example:
            value:
              data:
                id: <string>
                type: memberships
                attributes:
                  name: <string>
                  email: <string>
                  created_at: '2023-11-07T05:31:56Z'
                  updated_at: '2023-11-07T05:31:56Z'
                relationships:
                  role:
                    data:
                      type: predefinedRoles
                      id: <string>
                links:
                  self:
                    href: <string>
                    rel: <string>
                    describedby: <string>
                    title: <string>
                    type: <string>
                    hreflang: <string>
                    meta: {}
        description: '`MembershipResource`'
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
    MembershipResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
          enum:
            - memberships
        attributes:
          type: object
          properties:
            name:
              type: string
            email:
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
            - email
            - created_at
            - updated_at
        relationships:
          type: object
          properties:
            role:
              type: object
              properties:
                data:
                  anyOf:
                    - $ref: '#/components/schemas/RoleResourceIdentifier'
                    - type: 'null'
              required:
                - data
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
      title: MembershipResource
    RoleResourceIdentifier:
      type: object
      properties:
        type:
          type: string
          enum:
            - predefinedRoles
        id:
          type: string
      required:
        - type
        - id
      title: RoleResourceIdentifier

````