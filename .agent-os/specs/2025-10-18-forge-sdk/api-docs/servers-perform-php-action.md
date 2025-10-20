---
source: https://forge.laravel.com/docs/api-reference/servers/perform-php-action.md
fetched: 2025-10-19T14:39:58.829Z
---

# Perform PHP action

> Run an action on a specific PHP version.

Processing mode: <small><code>async</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi post /orgs/{organization}/servers/{server}/services/php/actions
paths:
  path: /orgs/{organization}/servers/{server}/services/php/actions
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
                  - $ref: '#/components/schemas/PhpAction'
              version:
                allOf:
                  - $ref: '#/components/schemas/PhpVersion'
            required: true
            title: PhpServiceActionRequest
            refIdentifier: '#/components/schemas/PhpServiceActionRequest'
            requiredProperties:
              - action
              - version
        examples:
          example:
            value:
              action: reboot
              version: php5
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
    PhpAction:
      type: string
      description: |-
        | |
        |---|
        | `reboot` <br/> Reboot PHP-FPM for a specific version |
      enum:
        - reboot
      title: PhpAction
    PhpVersion:
      type: string
      enum:
        - php5
        - php56-old
        - php56
        - php70
        - php71
        - php72
        - php73
        - php74
        - php80
        - php81
        - php82
        - php83
        - php84
        - php85
      title: PhpVersion

````