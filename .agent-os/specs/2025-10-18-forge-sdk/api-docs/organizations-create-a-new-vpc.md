---
source: https://forge.laravel.com/docs/api-reference/organizations/create-a-new-vpc.md
fetched: 2025-10-19T14:37:19.006Z
---

# Create a new VPC

> Create a private network for the provider.

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi post /orgs/{organization}/server-credentials/{credential}/regions/{region}/vpcs
paths:
  path: /orgs/{organization}/server-credentials/{credential}/regions/{region}/vpcs
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
        credential:
          schema:
            - type: integer
              required: true
              description: The credential ID
        region:
          schema:
            - type: string
              required: true
      query: {}
      header: {}
      cookie: {}
    body:
      application/json:
        schemaArray:
          - type: object
            properties:
              name:
                allOf:
                  - type: string
                    maxLength: 255
            required: true
            title: CreateServerProviderNetworkRequest
            refIdentifier: '#/components/schemas/CreateServerProviderNetworkRequest'
            requiredProperties:
              - name
        examples:
          example:
            value:
              name: <string>
  response:
    '200':
      application/json:
        schemaArray:
          - type: object
            properties: {}
        examples:
          example:
            value: {}
        description: ''
    '201':
      application/vnd.api+json:
        schemaArray:
          - type: object
            properties:
              data:
                allOf:
                  - $ref: '#/components/schemas/VpcResource'
            requiredProperties:
              - data
        examples:
          example:
            value:
              data:
                id: <string>
                type: vpcs
                attributes:
                  name: my-vpc
                  cidrBlock: <string>
                  subnets: <string>
                  region: <string>
                links:
                  self:
                    href: <string>
                    rel: <string>
                    describedby: <string>
                    title: <string>
                    type: <string>
                    hreflang: <string>
                    meta: {}
        description: '`VpcResource`'
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
    VpcResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
          enum:
            - vpcs
        attributes:
          type: object
          properties:
            name:
              type: string
              description: The name of the vpc
              examples:
                - my-vpc
            cidrBlock:
              type: string
            subnets:
              type: string
            region:
              type: string
          required:
            - name
            - cidrBlock
            - subnets
            - region
        links:
          type: object
          properties:
            self:
              description: A link to the resource itself
              $ref: '#/components/schemas/Link'
          required:
            - self
      required:
        - id
        - type
        - links
      title: VpcResource

````