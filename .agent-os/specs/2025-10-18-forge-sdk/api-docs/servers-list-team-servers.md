---
source: https://forge.laravel.com/docs/api-reference/servers/list-team-servers.md
fetched: 2025-10-19T14:41:08.638Z
---

# List team servers

> Show all servers for the team.

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi get /orgs/{organization}/teams/{team}/servers
paths:
  path: /orgs/{organization}/teams/{team}/servers
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
                      $ref: '#/components/schemas/ServerResource'
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
                      $ref: '#/components/schemas/TagResource'
            requiredProperties:
              - data
              - links
              - meta
        examples:
          example:
            value:
              data:
                - id: <string>
                  type: servers
                  attributes:
                    id: 123
                    credential_id: 123
                    name: <string>
                    type: <string>
                    ubuntu_version: <string>
                    ssh_port: 123
                    provider: <string>
                    identifier: <string>
                    size: <string>
                    region: <string>
                    php_version: <string>
                    php_cli_version: <string>
                    opcache_status: app
                    database_type: <string>
                    db_status: <string>
                    redis_status: <string>
                    ip_address: <string>
                    private_ip_address: <string>
                    revoked: true
                    created_at: '2025-07-29T09:00:00Z'
                    updated_at: '2025-07-30T09:00:00Z'
                    connection_status: <string>
                    timezone: <string>
                    local_public_key: <string>
                    is_ready: true
                  relationships:
                    tags:
                      data:
                        - type: tags
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
                  type: tags
                  attributes:
                    name: <string>
                    created_at: '2025-07-29T09:00:00Z'
                    updated_at: '2025-07-30T09:00:00Z'
        description: Paginated set of `ServerResource`
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
    ServerResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
          enum:
            - servers
        attributes:
          type: object
          properties:
            id:
              type: integer
            credential_id:
              type:
                - integer
                - 'null'
            name:
              type: string
            type:
              type: string
            ubuntu_version:
              type:
                - string
                - 'null'
            ssh_port:
              type: integer
            provider:
              type: string
            identifier:
              type:
                - string
                - 'null'
            size:
              type: string
            region:
              type: string
            php_version:
              type:
                - string
                - 'null'
            php_cli_version:
              type:
                - string
                - 'null'
            opcache_status:
              description: The type of server.
              examples:
                - app
              $ref: '#/components/schemas/ServerType'
            database_type:
              type:
                - string
                - 'null'
            db_status:
              type:
                - string
                - 'null'
            redis_status:
              type:
                - string
                - 'null'
            ip_address:
              type:
                - string
                - 'null'
            private_ip_address:
              type:
                - string
                - 'null'
            revoked:
              type: boolean
            created_at:
              type: string
              format: date-time
              description: The date and time the server was created.
              examples:
                - '2025-07-29T09:00:00Z'
            updated_at:
              type: string
              format: date-time
              description: The date and time the server was last updated.
              examples:
                - '2025-07-30T09:00:00Z'
            connection_status:
              type: string
            timezone:
              type: string
            local_public_key:
              type:
                - string
                - 'null'
            is_ready:
              type: boolean
          required:
            - id
            - credential_id
            - name
            - type
            - ubuntu_version
            - ssh_port
            - provider
            - identifier
            - size
            - region
            - php_version
            - php_cli_version
            - opcache_status
            - database_type
            - db_status
            - redis_status
            - ip_address
            - private_ip_address
            - revoked
            - created_at
            - updated_at
            - connection_status
            - timezone
            - local_public_key
            - is_ready
        relationships:
          type: object
          properties:
            tags:
              type: object
              properties:
                data:
                  type: array
                  items:
                    $ref: '#/components/schemas/TagResourceIdentifier'
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
      title: ServerResource
    ServerType:
      type: string
      enum:
        - app
        - web
        - loadbalancer
        - database
        - cache
        - worker
        - meilisearch
      title: ServerType
    TagResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
          enum:
            - tags
        attributes:
          type: object
          properties:
            name:
              type: string
            created_at:
              type: string
              format: date-time
              description: The date and time the tag was created.
              examples:
                - '2025-07-29T09:00:00Z'
            updated_at:
              type: string
              format: date-time
              description: The date and time the tag was last updated.
              examples:
                - '2025-07-30T09:00:00Z'
          required:
            - name
            - created_at
            - updated_at
      required:
        - id
        - type
      title: TagResource
    TagResourceIdentifier:
      type: object
      properties:
        type:
          type: string
          enum:
            - tags
        id:
          type: string
      required:
        - type
        - id
      title: TagResourceIdentifier

````