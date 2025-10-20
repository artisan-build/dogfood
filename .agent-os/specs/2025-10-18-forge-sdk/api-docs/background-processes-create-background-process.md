---
source: https://forge.laravel.com/docs/api-reference/background-processes/create-background-process.md
fetched: 2025-10-19T14:34:17.311Z
---

# Create background process

> Create a new background process from a template.

Processing mode: <small><code>async</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi post /orgs/{organization}/servers/{server}/background-processes
paths:
  path: /orgs/{organization}/servers/{server}/background-processes
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
        server:
          schema:
            - type: integer
              required: true
              description: The server ID
      query: {}
      header: {}
      cookie: {}
    body:
      application/json:
        schemaArray:
          - type: object
            properties:
              name:
                allOf:
                  - type: string
                    description: The name of the background process.
                    example: Custom command runner
              command:
                allOf:
                  - type: string
                    description: The command to run.
                    example: php artisan custom:command
              user:
                allOf:
                  - type: string
                    description: The user to run the background process as.
                    enum:
                      - root
                      - forge
                    example: forge
              processes:
                allOf:
                  - type: integer
                    description: The number of processes to run.
                    example: 1
                    minimum: 1
              startsecs:
                allOf:
                  - type: integer
                    description: The number of seconds to wait before starting the process.
                    example: 10
                    minimum: 0
              stopwaitsecs:
                allOf:
                  - type: integer
                    description: The number of seconds to wait before stopping the process.
                    example: 10
                    minimum: 0
              stopsignal:
                allOf:
                  - type:
                      - string
                      - 'null'
                    description: The signal to send to stop the process.
                    example: SIGTERM
            required: true
            title: CreateBackgroundProcessRequest
            refIdentifier: '#/components/schemas/CreateBackgroundProcessRequest'
            requiredProperties:
              - name
              - command
              - user
              - processes
        examples:
          example:
            value:
              name: Custom command runner
              command: php artisan custom:command
              user: forge
              processes: 1
              startsecs: 10
              stopwaitsecs: 10
              stopsignal: SIGTERM
  response:
    '202':
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