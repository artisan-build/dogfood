---
source: https://forge.laravel.com/docs/api-reference/ssh-keys/list-server-ssh-keys.md
fetched: 2025-10-19T14:38:49.035Z
---

# List server SSH keys

> List all SSH keys associated with the server.

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi get /orgs/{organization}/servers/{server}/ssh-keys
paths:
  path: /orgs/{organization}/servers/{server}/ssh-keys
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
      query:
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
        filter[user]:
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
                      $ref: '#/components/schemas/KeyResource'
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
            requiredProperties:
              - data
              - links
              - meta
        examples:
          example:
            value:
              data:
                - id: <string>
                  type: keys
                  attributes:
                    name: <string>
                    user: <string>
                    status: <string>
                    created_by: 123
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
        description: Paginated set of `KeyResource`
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
    KeyResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
          enum:
            - keys
        attributes:
          type: object
          properties:
            name:
              type: string
            user:
              type: string
            status:
              type: string
            created_by:
              type:
                - integer
                - 'null'
              description: The user that created the key.
            created_at:
              type: string
              format: date-time
              description: The date the key was created.
            updated_at:
              type: string
              format: date-time
              description: The date the key was last updated.
          required:
            - name
            - user
            - status
            - created_by
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
      title: KeyResource
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