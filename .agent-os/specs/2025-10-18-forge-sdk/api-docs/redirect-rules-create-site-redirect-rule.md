---
source: https://forge.laravel.com/docs/api-reference/redirect-rules/create-site-redirect-rule.md
fetched: 2025-10-19T14:38:20.007Z
---

# Create site redirect rule

> Add a new redirect rule to the site.

Processing mode: <small><code>async</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi post /orgs/{organization}/servers/{server}/sites/{site}/redirect-rules
paths:
  path: /orgs/{organization}/servers/{server}/sites/{site}/redirect-rules
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
              from:
                allOf:
                  - type: string
                    description: The source URL path for the redirect rule.
                    example: /old-path
                    maxLength: 1000
              to:
                allOf:
                  - type: string
                    description: The destination URL path for the redirect rule.
                    example: /new-path
                    maxLength: 1000
              type:
                allOf:
                  - description: The type of the redirect rule.
                    example: permanent
                    $ref: '#/components/schemas/RedirectRuleType'
            required: true
            title: CreateRedirectRequest
            refIdentifier: '#/components/schemas/CreateRedirectRequest'
            requiredProperties:
              - from
              - to
              - type
        examples:
          example:
            value:
              from: /old-path
              to: /new-path
              type: permanent
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
    RedirectRuleType:
      type: string
      enum:
        - redirect
        - permanent
      title: RedirectRuleType

````