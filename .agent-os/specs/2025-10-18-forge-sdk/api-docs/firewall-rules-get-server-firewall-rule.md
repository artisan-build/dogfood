---
source: https://forge.laravel.com/docs/api-reference/firewall-rules/get-server-firewall-rule.md
fetched: 2025-10-19T14:35:51.659Z
---

# Get server firewall rule

> Get a specific firewall rule associated with the server.

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi get /orgs/{organization}/servers/{server}/firewall-rules/{rule}
paths:
  path: /orgs/{organization}/servers/{server}/firewall-rules/{rule}
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
        rule:
          schema:
            - type: integer
              required: true
              description: The rule ID
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
                  - $ref: '#/components/schemas/RuleResource'
            requiredProperties:
              - data
        examples:
          example:
            value:
              data:
                id: <string>
                type: rules
                attributes:
                  name: Allow HTTP
                  port: '80'
                  type: allow
                  ip_address: 192.168.1.1
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
        description: '`RuleResource`'
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
    RuleResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
          enum:
            - rules
        attributes:
          type: object
          properties:
            name:
              type: string
              description: The name of the firewall rule.
              examples:
                - Allow HTTP
            port:
              type:
                - string
                - 'null'
              description: The port or port range for the firewall rule.
              examples:
                - '80'
            type:
              type: string
              description: The type of the firewall rule.
              examples:
                - allow
            ip_address:
              type:
                - string
                - 'null'
              description: The IP address or subnet for the firewall rule.
              examples:
                - 192.168.1.1
            status:
              type:
                - string
                - 'null'
              description: The status of the firewall rule.
              examples:
                - installed
            created_at:
              type: string
              format: date-time
              description: The date and time the firewall rule was created.
              examples:
                - '2025-07-29T09:00:00Z'
            updated_at:
              type: string
              format: date-time
              description: The date and time the firewall rule was last updated.
              examples:
                - '2025-07-30T09:00:00Z'
          required:
            - name
            - port
            - type
            - ip_address
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
      title: RuleResource

````