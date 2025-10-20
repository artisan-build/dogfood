---
source: https://forge.laravel.com/docs/api-reference/sites/update-site.md
fetched: 2025-10-19T14:41:28.496Z
---

# Update site

> Update a site on the server.

Processing mode: <small><code>async</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi put /orgs/{organization}/servers/{server}/sites/{site}
paths:
  path: /orgs/{organization}/servers/{server}/sites/{site}
  method: put
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
        site:
          schema:
            - type: integer
              required: true
              description: The site ID
      query: {}
      header: {}
      cookie: {}
    body:
      application/json:
        schemaArray:
          - type: object
            properties:
              directory:
                allOf:
                  - type:
                      - string
                      - 'null'
              type:
                allOf:
                  - $ref: '#/components/schemas/SiteType'
              php_version:
                allOf:
                  - $ref: '#/components/schemas/PhpVersion'
              push_to_deploy:
                allOf:
                  - type: boolean
                    description: >-
                      Automatically trigger a new deployment when changes are
                      pushed to the environment's Git branch.
                    default: false
              repository_branch:
                allOf:
                  - type:
                      - string
                      - 'null'
            title: UpdateSiteRequest
            refIdentifier: '#/components/schemas/UpdateSiteRequest'
        examples:
          example:
            value:
              directory: <string>
              type: laravel
              php_version: php5
              push_to_deploy: false
              repository_branch: <string>
  response:
    '202':
      _mintlify/placeholder:
        schemaArray:
          - type: any
            description: Accepted
        examples: {}
        description: Accepted
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
    SiteType:
      type: string
      enum:
        - laravel
        - symfony
        - statamic
        - wordpress
        - phpmyadmin
        - php
        - next.js
        - nuxt.js
        - static-html
        - other
        - custom
      title: SiteType

````