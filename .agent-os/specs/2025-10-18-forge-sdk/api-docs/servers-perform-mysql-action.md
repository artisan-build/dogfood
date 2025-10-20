---
source: https://forge.laravel.com/docs/api-reference/servers/perform-mysql-action.md
fetched: 2025-10-19T14:39:56.324Z
---

# Perform MySQL action

> Run an action on the MySQL service.

Processing mode: <small><code>async</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi post /orgs/{organization}/servers/{server}/services/mysql/actions
paths:
  path: /orgs/{organization}/servers/{server}/services/mysql/actions
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
                  - $ref: '#/components/schemas/MysqlAction'
            required: true
            title: MysqlServiceActionRequest
            refIdentifier: '#/components/schemas/MysqlServiceActionRequest'
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
    MysqlAction:
      type: string
      description: |-
        | |
        |---|
        | `reboot` <br/> Reboot the MySQL service |
        | `stop` <br/> Stop the MySQL service |
      enum:
        - reboot
        - stop
      title: MysqlAction

````