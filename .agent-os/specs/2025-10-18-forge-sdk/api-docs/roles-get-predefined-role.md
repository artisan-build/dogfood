---
source: https://forge.laravel.com/docs/api-reference/roles/get-predefined-role.md
fetched: 2025-10-19T14:38:29.076Z
---

# Get predefined role

> Show a specific predefined role.

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi get /predefined-roles/{role}
paths:
  path: /predefined-roles/{role}
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
        role:
          schema:
            - type: integer
              required: true
              description: The role ID
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
                  - $ref: '#/components/schemas/PredefinedRoleResource'
            requiredProperties:
              - data
        examples:
          example:
            value:
              data:
                id: <string>
                type: predefinedRoles
                attributes:
                  name: <string>
                  created_at: '2023-11-07T05:31:56Z'
                  updated_at: '2023-11-07T05:31:56Z'
                relationships:
                  permissions:
                    data:
                      - type: permissions
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
        description: '`PredefinedRoleResource`'
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
    PermissionResourceIdentifier:
      type: object
      properties:
        type:
          type: string
          enum:
            - permissions
        id:
          type: string
      required:
        - type
        - id
      title: PermissionResourceIdentifier
    PredefinedRoleResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
          enum:
            - predefinedRoles
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
        relationships:
          type: object
          properties:
            permissions:
              type: object
              properties:
                data:
                  type: array
                  items:
                    $ref: '#/components/schemas/PermissionResourceIdentifier'
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
      title: PredefinedRoleResource

````