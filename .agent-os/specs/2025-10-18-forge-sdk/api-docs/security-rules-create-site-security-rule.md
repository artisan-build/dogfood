---
source: https://forge.laravel.com/docs/api-reference/security-rules/create-site-security-rule.md
fetched: 2025-10-19T14:39:16.369Z
---

# Create site security rule

> Add a new security rule to the site.

Processing mode: <small><code>async</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi post /orgs/{organization}/servers/{server}/sites/{site}/security-rules
paths:
  path: /orgs/{organization}/servers/{server}/sites/{site}/security-rules
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
              name:
                allOf:
                  - type: string
                    description: The name of the security rule.
                    example: Restricted Access
                    maxLength: 255
              path:
                allOf:
                  - type: string
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
                        - password
                    minItems: 1
            required: true
            title: CreateSecurityRuleRequest
            refIdentifier: '#/components/schemas/CreateSecurityRuleRequest'
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
      application/vnd.api+json:
        schemaArray:
          - type: object
            properties:
              data:
                allOf:
                  - $ref: '#/components/schemas/SecurityRuleResource'
            requiredProperties:
              - data
        examples:
          example:
            value:
              data:
                id: <string>
                type: securityRules
                attributes:
                  name: Restricted Access
                  path: /admin
                  status: installed
                  created_at: '2025-07-29T09:00:00Z'
                  updated_at: '2025-07-30T09:00:00Z'
                links:
                  self:
                    href: <string>
                    rel: <string>
                    describedby: <string>
                    title: <string>
                    type: <string>
                    hreflang: <string>
                    meta: {}
        description: '`SecurityRuleResource`'
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
    SecurityRuleResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
          enum:
            - securityRules
        attributes:
          type: object
          properties:
            name:
              type: string
              description: The name of the security rule.
              examples:
                - Restricted Access
            path:
              type:
                - string
                - 'null'
              description: The path for the security rule.
              examples:
                - /admin
            status:
              type:
                - string
                - 'null'
              description: The status of the security rule.
              examples:
                - installed
            created_at:
              type: string
              format: date-time
              description: The date and time the security rule was created.
              examples:
                - '2025-07-29T09:00:00Z'
            updated_at:
              type: string
              format: date-time
              description: The date and time the security rule was last updated.
              examples:
                - '2025-07-30T09:00:00Z'
          required:
            - name
            - path
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
      title: SecurityRuleResource

````