---
source: https://forge.laravel.com/docs/api-reference/providers/get-provider.md
fetched: 2025-10-19T14:37:26.635Z
---

# Get provider

> Show the provider.

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi get /providers/{provider}
paths:
  path: /providers/{provider}
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
                  - $ref: '#/components/schemas/ProviderResource'
            requiredProperties:
              - data
        examples:
          example:
            value:
              data:
                id: <string>
                type: providers
                attributes:
                  name: <string>
                  slug: <string>
                  simple_name: <string>
                  currency: <string>
                  currency_symbol: <string>
                  default_size_code: <string>
                  default_region_code: <string>
                links:
                  self:
                    href: <string>
                    rel: <string>
                    describedby: <string>
                    title: <string>
                    type: <string>
                    hreflang: <string>
                    meta: {}
        description: '`ProviderResource`'
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
    ProviderResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
          enum:
            - providers
        attributes:
          type: object
          properties:
            name:
              type: string
            slug:
              type: string
            simple_name:
              type:
                - string
                - 'null'
            currency:
              type: string
            currency_symbol:
              type: string
            default_size_code:
              type:
                - string
                - 'null'
            default_region_code:
              type:
                - string
                - 'null'
          required:
            - name
            - slug
            - simple_name
            - currency
            - currency_symbol
            - default_size_code
            - default_region_code
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
      title: ProviderResource

````