---
source: https://forge.laravel.com/docs/api-reference/security-rules/delete-site-security-rule.md
fetched: 2025-10-19T14:39:23.476Z
---

# Delete site security rule

> Remove a security rule from the site.

Processing mode: <small><code>async</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi delete /orgs/{organization}/servers/{server}/sites/{site}/security-rules/{securityRule}
paths:
  path: >-
    /orgs/{organization}/servers/{server}/sites/{site}/security-rules/{securityRule}
  method: delete
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
        securityRule:
          schema:
            - type: integer
              required: true
              description: The security rule ID
      query: {}
      header: {}
      cookie: {}
    body: {}
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
  deprecated: false
  type: path
  xMint:
    metadata:
      noindex: true
components:
  schemas: {}

````