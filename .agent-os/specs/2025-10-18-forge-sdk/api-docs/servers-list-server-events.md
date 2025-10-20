---
source: https://forge.laravel.com/docs/api-reference/servers/list-server-events.md
fetched: 2025-10-19T14:40:08.705Z
---

# List server events

> List all events associated with the server.

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi get /orgs/{organization}/servers/{server}/events
paths:
  path: /orgs/{organization}/servers/{server}/events
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
        include:
          schema:
            - type: string
              description: >-
                Available includes are `initiator`, `initiatorCount`,
                `initiatorExists`. You can include multiple options by
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
        filter[initiated_by]:
          schema:
            - type: string
              description: The user ID of the event initiator.
              examples:
                - 1
              example: 1
        filter[ran_as]:
          schema:
            - type: string
              description: The server user that the event was run as.
              examples:
                - forge
              example: forge
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
                      $ref: '#/components/schemas/EventResource'
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
                      $ref: '#/components/schemas/UserResource'
            requiredProperties:
              - data
              - links
              - meta
        examples:
          example:
            value:
              data:
                - id: <string>
                  type: events
                  attributes:
                    description: Adding database
                    ran_as: forge
                    created_at: '2025-07-29T09:00:00Z'
                    updated_at: '2025-07-30T09:00:00Z'
                  relationships:
                    initiator:
                      data:
                        type: users
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
                  type: users
                  attributes:
                    name: <string>
                    email: <string>
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
        description: Paginated set of `EventResource`
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
    EventResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
          enum:
            - events
        attributes:
          type: object
          properties:
            description:
              type: string
              description: The description of the event.
              examples:
                - Adding database
            ran_as:
              type:
                - string
                - 'null'
              description: The server user that the event was run as.
              examples:
                - forge
            created_at:
              type: string
              format: date-time
              description: The date and time the event was created.
              examples:
                - '2025-07-29T09:00:00Z'
            updated_at:
              type: string
              format: date-time
              description: The date and time the event was last updated.
              examples:
                - '2025-07-30T09:00:00Z'
          required:
            - description
            - ran_as
            - created_at
            - updated_at
        relationships:
          type: object
          properties:
            initiator:
              type: object
              properties:
                data:
                  anyOf:
                    - $ref: '#/components/schemas/UserResourceIdentifier'
                    - type: 'null'
              required:
                - data
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
      title: EventResource
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
    UserResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
          enum:
            - users
        attributes:
          type: object
          properties:
            name:
              type: string
            email:
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
            - email
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
      title: UserResource
    UserResourceIdentifier:
      type: object
      properties:
        type:
          type: string
          enum:
            - users
        id:
          type: string
      required:
        - type
        - id
      title: UserResourceIdentifier

````