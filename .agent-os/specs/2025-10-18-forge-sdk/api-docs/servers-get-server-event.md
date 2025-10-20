---
source: https://forge.laravel.com/docs/api-reference/servers/get-server-event.md
fetched: 2025-10-19T14:40:11.169Z
---

# Get server event

> Get a specific event associated with the server.

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi get /orgs/{organization}/servers/{server}/events/{event}
paths:
  path: /orgs/{organization}/servers/{server}/events/{event}
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
        event:
          schema:
            - type: integer
              required: true
              description: The event ID
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
                  - $ref: '#/components/schemas/EventResource'
            requiredProperties:
              - data
        examples:
          example:
            value:
              data:
                id: <string>
                type: events
                attributes:
                  description: Adding database
                  ran_as: forge
                  created_at: '2025-07-29T09:00:00Z'
                  updated_at: '2025-07-30T09:00:00Z'
                relationships:
                  initiator:
                    data:
                      type: users
                      id: <string>
                links:
                  self:
                    href: <string>
                    rel: <string>
                    describedby: <string>
                    title: <string>
                    type: <string>
                    hreflang: <string>
                    meta: {}
        description: '`EventResource`'
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
    EventResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
          enum:
            - events
        attributes:
          type: object
          properties:
            description:
              type: string
              description: The description of the event.
              examples:
                - Adding database
            ran_as:
              type:
                - string
                - 'null'
              description: The server user that the event was run as.
              examples:
                - forge
            created_at:
              type: string
              format: date-time
              description: The date and time the event was created.
              examples:
                - '2025-07-29T09:00:00Z'
            updated_at:
              type: string
              format: date-time
              description: The date and time the event was last updated.
              examples:
                - '2025-07-30T09:00:00Z'
          required:
            - description
            - ran_as
            - created_at
            - updated_at
        relationships:
          type: object
          properties:
            initiator:
              type: object
              properties:
                data:
                  anyOf:
                    - $ref: '#/components/schemas/UserResourceIdentifier'
                    - type: 'null'
              required:
                - data
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
      title: EventResource
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
    UserResourceIdentifier:
      type: object
      properties:
        type:
          type: string
          enum:
            - users
        id:
          type: string
      required:
        - type
        - id
      title: UserResourceIdentifier

````