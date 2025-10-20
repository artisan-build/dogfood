---
source: https://forge.laravel.com/docs/api-reference/organizations/get-vpc.md
fetched: 2025-10-19T14:37:21.637Z
---

# Get VPC

> Get a VPC for the provider.

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi get /orgs/{organization}/server-credentials/{credential}/regions/{region}/vpcs/{vpcId}
paths:
  path: >-
    /orgs/{organization}/server-credentials/{credential}/regions/{region}/vpcs/{vpcId}
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
        credential:
          schema:
            - type: integer
              required: true
              description: The credential ID
        region:
          schema:
            - type: string
              required: true
        vpcId:
          schema:
            - type: string
              required: true
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