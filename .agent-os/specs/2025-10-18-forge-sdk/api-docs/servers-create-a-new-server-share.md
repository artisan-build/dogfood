---
source: https://forge.laravel.com/docs/api-reference/servers/create-a-new-server-share.md
fetched: 2025-10-19T14:41:11.205Z
---

# Create a new server share

> Share a server with a team.

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi post /orgs/{organization}/teams/{team}/servers
paths:
  path: /orgs/{organization}/teams/{team}/servers
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
        team:
          schema:
            - type: integer
              required: true
              description: The team ID
      query: {}
      header: {}
      cookie: {}
    body:
      application/json:
        schemaArray:
          - type: object
            properties:
              server_id:
                allOf:
                  - type: integer
                    description: The server ID
                    example: 12345
            required: true
            title: ShareServerRequest
            refIdentifier: '#/components/schemas/ShareServerRequest'
            requiredProperties:
              - server_id
        examples:
          example:
            value:
              server_id: 12345
  response:
    '201':
      application/vnd.api+json:
        schemaArray:
          - type: object
            properties:
              data:
                allOf:
                  - $ref: '#/components/schemas/ServerResource'
            requiredProperties:
              - data
        examples:
          example:
            value:
              data:
                id: <string>
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
        description: '`ServerResource`'
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