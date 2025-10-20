---
source: https://forge.laravel.com/docs/api-reference/servers/perform-an-action-on-a-server-background-process.md
fetched: 2025-10-19T14:39:44.190Z
---

# Perform an action on a server background process

> Run an action on a server-level background process.

Processing mode: <small><code>async</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi post /orgs/{organization}/servers/{server}/background-processes/{backgroundProcess}/actions
paths:
  path: >-
    /orgs/{organization}/servers/{server}/background-processes/{backgroundProcess}/actions
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
        backgroundProcess:
          schema:
            - type: integer
              required: true
              description: The background process ID
      query: {}
      header: {}
      cookie: {}
    body:
      application/json:
        schemaArray:
          - type: object
            properties:
              action:
                allOf:
                  - description: |-
                      The action to perform on the background process.
                      | |
                      |---|
                      | `restart` <br/> Restart the background process |
                      | `stop` <br/> Stop the background process |
                      | `start` <br/> Start the background process |
                      | `empty-log` <br/> Empty the background process log |
                    example: restart
                    $ref: '#/components/schemas/BackgroundProcessAction'
            required: true
            title: BackgroundProcessActionRequest
            refIdentifier: '#/components/schemas/BackgroundProcessActionRequest'
            requiredProperties:
              - action
        examples:
          example:
            value:
              action: restart
  response:
    '202': {}
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
    BackgroundProcessAction:
      type: string
      description: |-
        | |
        |---|
        | `restart` <br/> Restart the background process |
        | `stop` <br/> Stop the background process |
        | `start` <br/> Start the background process |
        | `empty-log` <br/> Empty the background process log |
      enum:
        - restart
        - stop
        - start
        - empty-log
      title: BackgroundProcessAction

````