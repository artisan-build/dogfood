---
source: https://forge.laravel.com/docs/api-reference/background-processes/get-background-process.md
fetched: 2025-10-19T14:34:19.418Z
---

# Get background process

> 

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi get /orgs/{organization}/servers/{server}/background-processes/{backgroundProcess}
paths:
  path: >-
    /orgs/{organization}/servers/{server}/background-processes/{backgroundProcess}
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
        backgroundProcess:
          schema:
            - type: integer
              required: true
              description: The background process ID
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
                  - $ref: '#/components/schemas/BackgroundProcessResource'
            requiredProperties:
              - data
        examples:
          example:
            value:
              data:
                id: <string>
                type: backgroundProcesses
                attributes:
                  command: php artisan queue:work database
                  user: forge
                  directory: /home/forge/forge.laravel.com
                  processes: 3
                  status: running
                  created_at: '2025-07-29T09:00:00Z'
        description: '`BackgroundProcessResource`'
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