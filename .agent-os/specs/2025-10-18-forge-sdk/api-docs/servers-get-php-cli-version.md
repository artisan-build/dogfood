---
source: https://forge.laravel.com/docs/api-reference/servers/get-php-cli-version.md
fetched: 2025-10-19T14:40:16.403Z
---

# Get PHP CLI version

> Show the PHP CLI version which has been set for the server.

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi get /orgs/{organization}/servers/{server}/php/cli-version
paths:
  path: /orgs/{organization}/servers/{server}/php/cli-version
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
                  - $ref: '#/components/schemas/PhpVersionResource'
            requiredProperties:
              - data
        examples:
          example:
            value:
              data:
                id: <string>
                type: phpVersions
                attributes:
                  version: '5.6'
                  binary_name: <string>
                  status: <string>
                  created_at: '2023-11-07T05:31:56Z'
                  updated_at: '2023-11-07T05:31:56Z'
                links:
                  self:
                    href: <string>
                    rel: <string>
                    describedby: <string>
                    title: <string>
                    type: <string>
                    hreflang: <string>
                    meta: {}
        description: '`PhpVersionResource`'
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
    PhpVersionResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
          enum:
            - phpVersions
        attributes:
          type: object
          properties:
            version:
              type: string
              enum:
                - '5.6'
                - '7.0'
                - '7.1'
                - '7.2'
                - '7.3'
                - '7.4'
                - '8.0'
                - '8.1'
                - '8.2'
                - '8.3'
                - '8.4'
                - '8.5'
                - '5'
            binary_name:
              type: string
            status:
              type: string
            created_at:
              type: string
              format: date-time
            updated_at:
              type: string
              format: date-time
          required:
            - version
            - binary_name
            - status
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
      title: PhpVersionResource

````