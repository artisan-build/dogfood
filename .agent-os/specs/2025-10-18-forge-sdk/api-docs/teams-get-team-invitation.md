---
source: https://forge.laravel.com/docs/api-reference/teams/get-team-invitation.md
fetched: 2025-10-19T14:43:19.744Z
---

# Get team invitation

> Show a pending invitation for the team.

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi get /orgs/{organization}/teams/{team}/invites/{invitation}
paths:
  path: /orgs/{organization}/teams/{team}/invites/{invitation}
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
        invitation:
          schema:
            - type: integer
              required: true
              description: The invitation ID
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
                  - $ref: '#/components/schemas/TeamInvitationResource'
            requiredProperties:
              - data
        examples:
          example:
            value:
              data:
                id: <string>
                type: teamInvitations
                attributes:
                  email: <string>
                  created_at: '2023-11-07T05:31:56Z'
                  updated_at: '2023-11-07T05:31:56Z'
                relationships:
                  role:
                    data:
                      type: predefinedRoles
                      id: <string>
                  team:
                    data:
                      type: teams
                      id: <string>
                  organization:
                    data:
                      type: organizations
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
        description: '`TeamInvitationResource`'
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
    OrganizationResourceIdentifier:
      type: object
      properties:
        type:
          type: string
          enum:
            - organizations
        id:
          type: string
      required:
        - type
        - id
      title: OrganizationResourceIdentifier
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
    TeamInvitationResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
          enum:
            - teamInvitations
        attributes:
          type: object
          properties:
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
            team:
              type: object
              properties:
                data:
                  anyOf:
                    - $ref: '#/components/schemas/TeamResourceIdentifier'
                    - type: 'null'
              required:
                - data
            organization:
              type: object
              properties:
                data:
                  anyOf:
                    - $ref: '#/components/schemas/OrganizationResourceIdentifier'
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
      title: TeamInvitationResource
    TeamResourceIdentifier:
      type: object
      properties:
        type:
          type: string
          enum:
            - teams
        id:
          type: string
      required:
        - type
        - id
      title: TeamResourceIdentifier

````