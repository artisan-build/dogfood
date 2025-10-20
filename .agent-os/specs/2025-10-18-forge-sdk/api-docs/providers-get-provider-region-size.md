---
source: https://forge.laravel.com/docs/api-reference/providers/get-provider-region-size.md
fetched: 2025-10-19T14:37:41.458Z
---

# Get provider region size

> Show the provider region size.

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi get /providers/{provider}/regions/{providerRegion}/sizes/{providerSize}
paths:
  path: /providers/{provider}/regions/{providerRegion}/sizes/{providerSize}
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
        providerRegion:
          schema:
            - type: integer
              required: true
              description: The provider region ID
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
                  - $ref: '#/components/schemas/ProviderRegionSizeResource'
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
                links:
                  self:
                    href: <string>
                    rel: <string>
                    describedby: <string>
                    title: <string>
                    type: <string>
                    hreflang: <string>
                    meta: {}
        description: '`ProviderRegionSizeResource`'
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
    ProviderRegionSizeResource:
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
          required:
            - name
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
      title: ProviderRegionSizeResource

````