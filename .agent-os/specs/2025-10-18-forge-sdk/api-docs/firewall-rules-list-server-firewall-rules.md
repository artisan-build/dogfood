---
source: https://forge.laravel.com/docs/api-reference/firewall-rules/list-server-firewall-rules.md
fetched: 2025-10-19T14:35:46.683Z
---

# List server firewall rules

> List all firewall rules associated with the server.

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi get /orgs/{organization}/servers/{server}/firewall-rules
paths:
  path: /orgs/{organization}/servers/{server}/firewall-rules
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
        sort:
          schema:
            - type: string
              description: >-
                Available sorts are `created_at`, `updated_at`. You can sort by
                multiple options by separating them with a comma. To sort in
                descending order, use `-` sign in front of the sort, for
                example: `-created_at`.
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
              description: The name of the firewall rule.
              examples:
                - Allow MySQL
              example: Allow MySQL
        filter[status]:
          schema:
            - type: string
              description: The status of the firewall rule.
              examples:
                - installed
              example: installed
        filter[ip_address]:
          schema:
            - type: string
              description: The IP address or subnet for the firewall rule.
              examples:
                - 192.168.1.1
              example: 192.168.1.1
        filter[type]:
          schema:
            - type: string
              description: The type of the firewall rule.
              examples:
                - allow
              example: allow
        filter[port]:
          schema:
            - type: string
              description: The port or port range for the firewall rule.
              examples:
                - 3306
              example: 3306
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
                      $ref: '#/components/schemas/RuleResource'
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
                  type: rules
                  attributes:
                    name: Allow HTTP
                    port: '80'
                    type: allow
                    ip_address: 192.168.1.1
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
        description: Paginated set of `RuleResource`
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
    RuleResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
          enum:
            - rules
        attributes:
          type: object
          properties:
            name:
              type: string
              description: The name of the firewall rule.
              examples:
                - Allow HTTP
            port:
              type:
                - string
                - 'null'
              description: The port or port range for the firewall rule.
              examples:
                - '80'
            type:
              type: string
              description: The type of the firewall rule.
              examples:
                - allow
            ip_address:
              type:
                - string
                - 'null'
              description: The IP address or subnet for the firewall rule.
              examples:
                - 192.168.1.1
            status:
              type:
                - string
                - 'null'
              description: The status of the firewall rule.
              examples:
                - installed
            created_at:
              type: string
              format: date-time
              description: The date and time the firewall rule was created.
              examples:
                - '2025-07-29T09:00:00Z'
            updated_at:
              type: string
              format: date-time
              description: The date and time the firewall rule was last updated.
              examples:
                - '2025-07-30T09:00:00Z'
          required:
            - name
            - port
            - type
            - ip_address
            - status
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
      title: RuleResource

````