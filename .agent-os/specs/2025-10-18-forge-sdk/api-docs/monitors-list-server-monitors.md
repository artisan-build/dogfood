---
source: https://forge.laravel.com/docs/api-reference/monitors/list-server-monitors.md
fetched: 2025-10-19T14:36:46.269Z
---

# List server monitors

> List all monitors associated with the server.

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi get /orgs/{organization}/servers/{server}/monitors
paths:
  path: /orgs/{organization}/servers/{server}/monitors
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
                Available sorts are `state`, `status`, `created_at`,
                `updated_at`. You can sort by multiple options by separating
                them with a comma. To sort in descending order, use `-` sign in
                front of the sort, for example: `-state`.
        page[size]:
          schema:
            - type: integer
              description: The number of results that will be returned per page.
              default: 30
        page[cursor]:
          schema:
            - type: string
              description: The cursor to start the pagination from.
        filter[status]:
          schema:
            - type: string
              description: The status of the monitor.
              examples:
                - installed
              example: installed
        filter[state]:
          schema:
            - type: string
              description: The state of the monitor.
              examples:
                - OK
              example: OK
        filter[type]:
          schema:
            - type: string
              description: The type of the monitor.
              examples:
                - cpu_load
              example: cpu_load
        filter[notify]:
          schema:
            - type: string
              description: >-
                The email address to notify when the monitor is in an alert
                state.
              examples:
                - taylor@laravel.com
              example: taylor@laravel.com
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
                      $ref: '#/components/schemas/MonitorResource'
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
                  type: monitors
                  attributes:
                    type: cpu_load
                    operator: gte
                    threshold: 90
                    minutes: 1
                    notify: taylor@laravel.com
                    status: installed
                    state: OK
                    state_changed_at: '2025-07-30T09:00:00Z'
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
        description: Paginated set of `MonitorResource`
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
    MonitorMetricType:
      type: string
      enum:
        - cpu_load
        - disk
        - free_memory
        - used_memory
      title: MonitorMetricType
    MonitorOperator:
      type: string
      enum:
        - gte
        - lte
      title: MonitorOperator
    MonitorResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
          enum:
            - monitors
        attributes:
          type: object
          properties:
            type:
              description: The type of the monitor.
              examples:
                - cpu_load
              $ref: '#/components/schemas/MonitorMetricType'
            operator:
              description: The operator used against the threshold.
              examples:
                - gte
              $ref: '#/components/schemas/MonitorOperator'
            threshold:
              type: number
              description: The threshold to alert on once breached.
              examples:
                - 90
            minutes:
              type:
                - integer
                - 'null'
              description: The frequency in minutes to evaluate the monitor.
              examples:
                - 1
            notify:
              type: string
              description: >-
                The email address to notify when the monitor is in an alert
                state.
              examples:
                - taylor@laravel.com
            status:
              description: The status of the monitor.
              examples:
                - installed
              $ref: '#/components/schemas/ResourceState'
            state:
              description: The state of the monitor.
              examples:
                - OK
              $ref: '#/components/schemas/MonitorState'
            state_changed_at:
              type:
                - string
                - 'null'
              format: date-time
              description: The date and time the monitor state was last changed.
              examples:
                - '2025-07-30T09:00:00Z'
            created_at:
              type: string
              format: date-time
              description: The date and time the monitor was created.
              examples:
                - '2025-07-29T09:00:00Z'
            updated_at:
              type: string
              format: date-time
              description: The date and time the monitor was last updated.
              examples:
                - '2025-07-30T09:00:00Z'
          required:
            - type
            - operator
            - threshold
            - minutes
            - notify
            - status
            - state
            - state_changed_at
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
      title: MonitorResource
    MonitorState:
      type: string
      enum:
        - OK
        - ALERT
        - UNKNOWN
      title: MonitorState
    ResourceState:
      type: string
      enum:
        - installing
        - installed
        - removing
        - restarting
        - stopping
        - stopped
        - starting
        - syncing
        - updating
        - disabling
        - disabled
        - enabling
        - running
        - restoring
        - deleting
        - failed
        - success
        - failed-unknown
        - failed-runner
      title: ResourceState

````