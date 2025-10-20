---
source: https://forge.laravel.com/docs/api-reference/security-rules/get-site-security-rule.md
fetched: 2025-10-19T14:39:18.838Z
---

# Get site security rule

> Get a specific security rule associated with the site.

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi get /orgs/{organization}/servers/{server}/sites/{site}/security-rules/{securityRule}
paths:
  path: >-
    /orgs/{organization}/servers/{server}/sites/{site}/security-rules/{securityRule}
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
    '200':
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