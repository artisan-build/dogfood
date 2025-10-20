---
source: https://forge.laravel.com/docs/api-reference/sites/get-heartbeat.md
fetched: 2025-10-19T14:42:46.203Z
---

# Get heartbeat

> Show a specific heartbeat for the site.

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi get /orgs/{organization}/servers/{server}/sites/{site}/heartbeats/{heartbeat}
paths:
  path: /orgs/{organization}/servers/{server}/sites/{site}/heartbeats/{heartbeat}
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
        heartbeat:
          schema:
            - type: integer
              required: true
              description: The heartbeat ID
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
                  - $ref: '#/components/schemas/HeartbeatResource'
            requiredProperties:
              - data
        examples:
          example:
            value:
              data:
                id: <string>
                type: heartbeats
                attributes:
                  name: <string>
                  status: pending
                  grace_period: 1
                  frequency: 1
                  custom_frequency: <string>
                  ping_url: <string>
                links:
                  self:
                    href: <string>
                    rel: <string>
                    describedby: <string>
                    title: <string>
                    type: <string>
                    hreflang: <string>
                    meta: {}
        description: '`HeartbeatResource`'
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
    HeartbeatFrequency:
      type: integer
      enum:
        - 1
        - 5
        - 10
        - 30
        - 60
        - 1440
        - 10080
        - 312480
        - -1
      title: HeartbeatFrequency
    HeartbeatGracePeriod:
      type: integer
      enum:
        - 1
        - 2
        - 5
        - 10
      title: HeartbeatGracePeriod
    HeartbeatResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
          enum:
            - heartbeats
        attributes:
          type: object
          properties:
            name:
              type: string
              description: The name of the heartbeat.
            status:
              anyOf:
                - $ref: '#/components/schemas/HeartbeatStatus'
                - type: 'null'
            grace_period:
              $ref: '#/components/schemas/HeartbeatGracePeriod'
            frequency:
              $ref: '#/components/schemas/HeartbeatFrequency'
            custom_frequency:
              type:
                - string
                - 'null'
            ping_url:
              type:
                - string
                - 'null'
          required:
            - name
            - status
            - grace_period
            - frequency
            - custom_frequency
            - ping_url
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
      title: HeartbeatResource
    HeartbeatStatus:
      type: string
      enum:
        - pending
        - beating
        - missing
      title: HeartbeatStatus
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

````