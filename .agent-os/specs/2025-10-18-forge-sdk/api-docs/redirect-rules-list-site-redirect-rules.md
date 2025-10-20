---
source: https://forge.laravel.com/docs/api-reference/redirect-rules/list-site-redirect-rules.md
fetched: 2025-10-19T14:38:18.007Z
---

# List site redirect rules

> List all redirect rules associated with the site.

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi get /orgs/{organization}/servers/{server}/sites/{site}/redirect-rules
paths:
  path: /orgs/{organization}/servers/{server}/sites/{site}/redirect-rules
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
        site:
          schema:
            - type: integer
              required: true
              description: The site ID
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
        filter[from]:
          schema:
            - type: string
              description: The source URL path for the redirect rule.
              examples:
                - /old-path
              example: /old-path
        filter[to]:
          schema:
            - type: string
              description: The destination URL path for the redirect rule.
              examples:
                - /new-path
              example: /new-path
        filter[type]:
          schema:
            - type: string
              description: The type of the redirect rule.
              examples:
                - permanent
              example: permanent
        filter[status]:
          schema:
            - type: string
              description: The status of the redirect rule.
              examples:
                - installed
              example: installed
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
                      $ref: '#/components/schemas/RedirectRuleResource'
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
                  type: redirect-rules
                  attributes:
                    from: /old-path
                    to: /new-path
                    type: permanent
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
        description: Paginated set of `RedirectRuleResource`
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
    RedirectRuleResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
          enum:
            - redirect-rules
        attributes:
          type: object
          properties:
            from:
              type: string
              description: The source URL path for the redirect rule.
              examples:
                - /old-path
            to:
              type: string
              description: The destination URL path for the redirect rule.
              examples:
                - /new-path
            type:
              description: The type of the redirect rule.
              examples:
                - permanent
              $ref: '#/components/schemas/RedirectRuleType'
            status:
              type: string
              description: The status of the redirect rule.
              enum:
                - installing
                - installed
                - removing
              examples:
                - installed
            created_at:
              type: string
              format: date-time
              description: The date and time the redirect rule was created.
              examples:
                - '2025-07-29T09:00:00Z'
            updated_at:
              type: string
              format: date-time
              description: The date and time the redirect rule was last updated.
              examples:
                - '2025-07-30T09:00:00Z'
          required:
            - from
            - to
            - type
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
      title: RedirectRuleResource
    RedirectRuleType:
      type: string
      enum:
        - redirect
        - permanent
      title: RedirectRuleType

````