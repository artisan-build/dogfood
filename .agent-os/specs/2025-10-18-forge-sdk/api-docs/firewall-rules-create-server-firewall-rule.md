---
source: https://forge.laravel.com/docs/api-reference/firewall-rules/create-server-firewall-rule.md
fetched: 2025-10-19T14:35:49.160Z
---

# Create server firewall rule

> Add a new firewall rule to the server.

Processing mode: <small><code>async</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi post /orgs/{organization}/servers/{server}/firewall-rules
paths:
  path: /orgs/{organization}/servers/{server}/firewall-rules
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
                    maxLength: 50
              port:
                allOf:
                  - type:
                      - string
                      - 'null'
              type:
                allOf:
                  - $ref: '#/components/schemas/RuleType'
              ip_address:
                allOf:
                  - type: object
            required: true
            title: CreateFirewallRuleRequest
            refIdentifier: '#/components/schemas/CreateFirewallRuleRequest'
            requiredProperties:
              - name
              - type
        examples:
          example:
            value:
              name: <string>
              port: <string>
              type: allow
              ip_address: {}
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
    RuleType:
      type: string
      enum:
        - allow
        - deny
      title: RuleType

````