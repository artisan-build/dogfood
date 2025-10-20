---
source: https://forge.laravel.com/docs/api-reference/servers/perform-redis-action.md
fetched: 2025-10-19T14:39:53.809Z
---

# Perform Redis action

> Run an action on the Redis service.

Processing mode: <small><code>async</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi post /orgs/{organization}/servers/{server}/services/redis/actions
paths:
  path: /orgs/{organization}/servers/{server}/services/redis/actions
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
                  - $ref: '#/components/schemas/RedisAction'
            required: true
            title: RedisServiceActionRequest
            refIdentifier: '#/components/schemas/RedisServiceActionRequest'
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
    RedisAction:
      type: string
      description: |-
        | |
        |---|
        | `reboot` <br/> Reboot the Redis service |
      enum:
        - reboot
      title: RedisAction

````