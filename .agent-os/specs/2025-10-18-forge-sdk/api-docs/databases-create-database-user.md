---
source: https://forge.laravel.com/docs/api-reference/databases/create-database-user.md
fetched: 2025-10-19T14:34:54.068Z
---

# Create database user

> Add a new database user to the server.

Processing mode: <small><code>async</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi post /orgs/{organization}/servers/{server}/database/users
paths:
  path: /orgs/{organization}/servers/{server}/database/users
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
                    description: The name of the database user to create.
                    example: james
              password:
                allOf:
                  - type: string
                    description: The password for the database user.
                    example: password
                    maxLength: 255
              read_only:
                allOf:
                  - type: boolean
                    description: >-
                      Whether the user should have read-only access to the
                      databases.
                    example: true
              database_ids:
                allOf:
                  - type: array
                    description: The IDs of the databases to assign the user to.
                    example:
                      - 1
                      - 2
                      - 3
                    items:
                      type: integer
            required: true
            title: CreateDatabaseUserRequest
            refIdentifier: '#/components/schemas/CreateDatabaseUserRequest'
            requiredProperties:
              - name
              - password
        examples:
          example:
            value:
              name: james
              password: password
              read_only: true
              database_ids:
                - 1
                - 2
                - 3
  response:
    '202':
      application/vnd.api+json:
        schemaArray:
          - type: object
            properties:
              data:
                allOf:
                  - $ref: '#/components/schemas/DatabaseUserResource'
            requiredProperties:
              - data
        examples:
          example:
            value:
              data:
                id: <string>
                type: databaseUsers
                attributes:
                  name: forge
                  status: installed
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
        description: '`DatabaseUserResource`'
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
    DatabaseUserResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
          enum:
            - databaseUsers
        attributes:
          type: object
          properties:
            name:
              type: string
              description: The name of the database user.
              examples:
                - forge
            status:
              type: string
              description: The status of the database user.
              enum:
                - installed
                - updating
                - installing
                - removing
              examples:
                - installed
            created_at:
              type: string
              format: date-time
              description: The date and time the database user was created.
              examples:
                - '2025-07-29T09:00:00Z'
            updated_at:
              type: string
              format: date-time
              description: The date and time the database user was last updated.
              examples:
                - '2025-07-30T09:00:00Z'
          required:
            - name
            - status
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
      title: DatabaseUserResource
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

````