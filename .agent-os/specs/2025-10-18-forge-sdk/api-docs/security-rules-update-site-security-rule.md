---
source: https://forge.laravel.com/docs/api-reference/security-rules/update-site-security-rule.md
fetched: 2025-10-19T14:39:21.059Z
---

# Update site security rule

> Update an existing security rule for a site.

Processing mode: <small><code>async</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi put /orgs/{organization}/servers/{server}/sites/{site}/security-rules/{securityRule}
paths:
  path: >-
    /orgs/{organization}/servers/{server}/sites/{site}/security-rules/{securityRule}
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
        securityRule:
          schema:
            - type: integer
              required: true
              description: The security rule ID
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
                    description: The name of the security rule.
                    example: Restricted Access
                    maxLength: 255
              path:
                allOf:
                  - type:
                      - string
                      - 'null'
                    description: The path for the security rule.
                    example: /admin
                    maxLength: 255
              credentials:
                allOf:
                  - type: array
                    description: The credentials for the security rule.
                    items:
                      type: object
                      properties:
                        username:
                          type: string
                          description: The usernames for the credential.
                          example: admin
                          maxLength: 50
                        password:
                          type: string
                          description: The passwords for the credential.
                          example: secret123
                      required:
                        - username
                    minItems: 1
            required: true
            title: UpdateSecurityRuleRequest
            refIdentifier: '#/components/schemas/UpdateSecurityRuleRequest'
            requiredProperties:
              - name
              - credentials
        examples:
          example:
            value:
              name: Restricted Access
              path: /admin
              credentials:
                - username: admin
                  password: secret123
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
  schemas: {}

````