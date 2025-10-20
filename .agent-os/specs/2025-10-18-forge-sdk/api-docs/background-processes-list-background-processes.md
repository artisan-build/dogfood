---
source: https://forge.laravel.com/docs/api-reference/background-processes/list-background-processes.md
fetched: 2025-10-19T14:34:14.643Z
---

# List background processes

> List all background processes on the server.

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi get /orgs/{organization}/servers/{server}/background-processes
paths:
  path: /orgs/{organization}/servers/{server}/background-processes
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
                Available sorts are `user`. You can sort by multiple options by
                separating them with a comma. To sort in descending order, use
                `-` sign in front of the sort, for example: `-user`.
        page[size]:
          schema:
            - type: integer
              description: The number of results that will be returned per page.
              default: 30
        page[cursor]:
          schema:
            - type: string
              description: The cursor to start the pagination from.
        filter[user]:
          schema:
            - type: string
              description: The user that the process is running as.
              examples:
                - forge
              example: forge
        filter[site_id]:
          schema:
            - type: string
              description: The site ID that the process is running for.
              examples:
                - 1
              example: 1
        filter[directory]:
          schema:
            - type: string
              description: The directory that the process is running in.
              examples:
                - /home/forge/forge.laravel.com
              example: /home/forge/forge.laravel.com
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
                      $ref: '#/components/schemas/BackgroundProcessResource'
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
                  type: backgroundProcesses
                  attributes:
                    command: php artisan queue:work database
                    user: forge
                    directory: /home/forge/forge.laravel.com
                    processes: 3
                    status: running
                    created_at: '2025-07-29T09:00:00Z'
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
        description: Paginated set of `BackgroundProcessResource`
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
    BackgroundProcessResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
          enum:
            - backgroundProcesses
        attributes:
          type: object
          properties:
            command:
              type: string
              description: The command that the background process is running.
              examples:
                - php artisan queue:work database
            user:
              type: string
              description: The user that the background process is running as.
              examples:
                - forge
            directory:
              type:
                - string
                - 'null'
              description: The directory that the background process is running in.
              examples:
                - /home/forge/forge.laravel.com
            processes:
              type: integer
              description: The number of processes that the background process is running.
              examples:
                - 3
            status:
              type: string
              description: The status of the background process.
              enum:
                - installing
                - installed
                - removing
                - restarting
                - starting
                - stopping
              examples:
                - running
            created_at:
              type: string
              format: date-time
              description: The date and time the background process was created.
              examples:
                - '2025-07-29T09:00:00Z'
          required:
            - command
            - user
            - directory
            - processes
            - status
            - created_at
      required:
        - id
        - type
      title: BackgroundProcessResource

````