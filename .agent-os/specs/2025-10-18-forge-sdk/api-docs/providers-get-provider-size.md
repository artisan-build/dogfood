---
source: https://forge.laravel.com/docs/api-reference/providers/get-provider-size.md
fetched: 2025-10-19T14:37:31.647Z
---

# Get provider size

> Show the provider size.

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi get /providers/{provider}/sizes/{providerSize}
paths:
  path: /providers/{provider}/sizes/{providerSize}
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
        provider:
          schema:
            - type: integer
              required: true
              description: The provider ID
        providerSize:
          schema:
            - type: integer
              required: true
              description: The provider size ID
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
                  - $ref: '#/components/schemas/ProviderSizeResource'
            requiredProperties:
              - data
        examples:
          example:
            value:
              data:
                id: <string>
                type: providerSizes
                attributes:
                  name: <string>
                  code: <string>
                  series: <string>
                  category: <string>
                  cpus: 123
                  disk_type: <string>
                  architecture: <string>
                  ram: 123
                  disk: 123
                links:
                  self:
                    href: <string>
                    rel: <string>
                    describedby: <string>
                    title: <string>
                    type: <string>
                    hreflang: <string>
                    meta: {}
        description: '`ProviderSizeResource`'
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
    ProviderSizeResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
          enum:
            - providerSizes
        attributes:
          type: object
          properties:
            name:
              type: string
              description: The name of the size.
            code:
              type: string
              description: The code identifier from the provider.
            series:
              type: string
              description: The series type.
            category:
              type: string
              description: The category name
            cpus:
              type: integer
              description: The number of CPUs.
            disk_type:
              type: string
              description: The type of disk.
            architecture:
              type: string
              description: The CPU architecture.
            ram:
              type: integer
              description: The amount of RAM in MB.
            disk:
              type: integer
              description: The amount of disk space in MB.
          required:
            - name
            - code
            - series
            - category
            - cpus
            - disk_type
            - architecture
            - ram
            - disk
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
      title: ProviderSizeResource

````