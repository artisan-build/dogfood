---
source: https://forge.laravel.com/docs/api-reference/servers/create-server-action.md
fetched: 2025-10-19T14:39:46.688Z
---

# Create server action

> Run an action on a server, defined by the action type parameter.

Processing mode: <small><code>async</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi post /orgs/{organization}/servers/{server}/actions
paths:
  path: /orgs/{organization}/servers/{server}/actions
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
              action:
                allOf:
                  - description: |-
                      The action to perform on the server.
                      | |
                      |---|
                      | `reboot` <br/> Reboot the server. |
                    example: reboot
                    $ref: '#/components/schemas/ServerAction'
            required: true
            title: ServerActionRequest
            refIdentifier: '#/components/schemas/ServerActionRequest'
            requiredProperties:
              - action
        examples:
          example:
            value:
              action: reboot
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
    ServerAction:
      type: string
      description: |-
        | |
        |---|
        | `reboot` <br/> Reboot the server. |
      enum:
        - reboot
      title: ServerAction

````