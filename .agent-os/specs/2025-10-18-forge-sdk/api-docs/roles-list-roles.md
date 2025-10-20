---
source: https://forge.laravel.com/docs/api-reference/roles/list-roles.md
fetched: 2025-10-19T14:38:36.022Z
---

# List roles

> Show all roles for the organization.

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi get /orgs/{organization}/roles
paths:
  path: /orgs/{organization}/roles
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
      query:
        include:
          schema:
            - type: string
              description: >-
                Available includes are `permissions`, `permissionsCount`,
                `permissionsExists`. You can include multiple options by
                separating them with a comma.
        page[size]:
          schema:
            - type: integer
              description: The number of results that will be returned per page.
              default: 30
        page[cursor]:
          schema:
            - type: string
              description: The cursor to start the pagination from.
        filter[name]:
          schema:
            - type: string
        filter[permissions.name]:
          schema:
            - type: string
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
                  - type: array
                    items:
                      $ref: '#/components/schemas/CustomRoleResource'
              links:
                allOf:
                  - type: object
                    properties:
                      first:
                        type: string
                      last:
                        type: string
                      prev:
                        type: string
                      next:
                        type: string
              meta:
                allOf:
                  - type: object
                    properties:
                      path:
                        type:
                          - string
                          - 'null'
                        description: Base path for paginator generated URLs.
                      per_page:
                        type: integer
                        description: Number of items shown per page.
                      next_cursor:
                        type:
                          - string
                          - 'null'
                        description: The "cursor" that points to the next set of items.
                      prev_cursor:
                        type:
                          - string
                          - 'null'
                        description: The "cursor" that points to the previous set of items.
                    required:
                      - path
                      - per_page
                      - next_cursor
                      - prev_cursor
              included:
                allOf:
                  - type: array
                    items:
                      $ref: '#/components/schemas/PermissionResource'
            requiredProperties:
              - data
              - links
              - meta
        examples:
          example:
            value:
              data:
                - id: <string>
                  type: customRoles
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
              links:
                first: <string>
                last: <string>
                prev: <string>
                next: <string>
              meta:
                path: <string>
                per_page: 123
                next_cursor: <string>
                prev_cursor: <string>
              included:
                - id: <string>
                  type: permissions
                  attributes:
                    name: <string>
                  links:
                    self:
                      href: <string>
                      rel: <string>
                      describedby: <string>
                      title: <string>
                      type: <string>
                      hreflang: <string>
                      meta: {}
        description: Paginated set of `CustomRoleResource`
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
    CustomRoleResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
          enum:
            - customRoles
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
      title: CustomRoleResource
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
    PermissionResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
          enum:
            - permissions
        attributes:
          type: object
          properties:
            name:
              type: string
          required:
            - name
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
      title: PermissionResource
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

````