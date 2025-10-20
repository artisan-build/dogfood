---
source: https://forge.laravel.com/docs/api-reference/databases/get-database-schema.md
fetched: 2025-10-19T14:34:44.145Z
---

# Get database schema

> Get a specific database schema associated with the server.

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi get /orgs/{organization}/servers/{server}/database/schemas/{database}
paths:
  path: /orgs/{organization}/servers/{server}/database/schemas/{database}
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
        server:
          schema:
            - type: integer
              required: true
              description: The server ID
        database:
          schema:
            - type: integer
              required: true
              description: The database ID
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