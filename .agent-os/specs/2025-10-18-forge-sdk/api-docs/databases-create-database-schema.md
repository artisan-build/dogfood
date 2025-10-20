---
source: https://forge.laravel.com/docs/api-reference/databases/create-database-schema.md
fetched: 2025-10-19T14:34:41.589Z
---

# Create database schema

> Add a new database schema to the server.

Processing mode: <small><code>async</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi post /orgs/{organization}/servers/{server}/database/schemas
paths:
  path: /orgs/{organization}/servers/{server}/database/schemas
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
                    description: The name of the database to create.
                    example: forge
                    maxLength: 63
              user:
                allOf:
                  - type:
                      - string
                      - 'null'
                    description: >-
                      The name of the database user to create. Only needed if a
                      new user should be created alongside the database.
                    example: james
              password:
                allOf:
                  - type:
                      - string
                      - 'null'
                    description: >-
                      The password for the database user. Only used if the user
                      is provided.
                    example: password
                    maxLength: 255
            required: true
            title: CreateDatabaseRequest
            refIdentifier: '#/components/schemas/CreateDatabaseRequest'
            requiredProperties:
              - name
        examples:
          example:
            value:
              name: forge
              user: james
              password: password
  response:
    '202':
      application/vnd.api+json:
        schemaArray:
          - type: object
            properties:
              data:
                allOf:
                  - $ref: '#/components/schemas/DatabaseResource'
            requiredProperties:
              - data
        examples:
          example:
            value:
              data:
                id: <string>
                type: databases
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
        description: '`DatabaseResource`'
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
    DatabaseResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
          enum:
            - databases
        attributes:
          type: object
          properties:
            name:
              type: string
              description: The name of the database schema.
              examples:
                - forge
            status:
              type: string
              description: The status of the database schema.
              examples:
                - installed
            created_at:
              type: string
              format: date-time
              description: The date and time the database schema was created.
              examples:
                - '2025-07-29T09:00:00Z'
            updated_at:
              type: string
              format: date-time
              description: The date and time the database schema was last updated.
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
      title: DatabaseResource
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