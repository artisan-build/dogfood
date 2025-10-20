---
source: https://forge.laravel.com/docs/api-reference/commands/get-command.md
fetched: 2025-10-19T14:34:34.024Z
---

# Get command

> Get a specific command run.

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi get /orgs/{organization}/servers/{server}/sites/{site}/commands/{command}
paths:
  path: /orgs/{organization}/servers/{server}/sites/{site}/commands/{command}
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
        command:
          schema:
            - type: integer
              required: true
              description: The command ID
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
                  - $ref: '#/components/schemas/CommandResource'
            requiredProperties:
              - data
        examples:
          example:
            value:
              data:
                id: <string>
                type: commands
                attributes:
                  command: nvm use 22
                  status: finished
                  duration: 5m
                  user_id: 1
                  created_at: '2025-07-29T09:00:00Z'
                  updated_at: '2025-07-29T09:00:00Z'
                relationships:
                  user:
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
        description: '`CommandResource`'
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
    CommandResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
          enum:
            - commands
        attributes:
          type: object
          properties:
            command:
              type: string
              description: The command that ran.
              examples:
                - nvm use 22
            status:
              description: The status of the command.
              examples:
                - finished
              $ref: '#/components/schemas/CommandStatus'
            duration:
              type: string
              description: The duration of the command in human-readable format.
              examples:
                - 5m
            user_id:
              type: integer
              description: The ID of the user who initiated the command.
              examples:
                - 1
            created_at:
              type: string
              format: date-time
              description: The date and time the command was created.
              examples:
                - '2025-07-29T09:00:00Z'
            updated_at:
              type: string
              format: date-time
              description: The date and time the command was last updated.
              examples:
                - '2025-07-29T09:00:00Z'
          required:
            - command
            - status
            - duration
            - user_id
            - created_at
            - updated_at
        relationships:
          type: object
          properties:
            user:
              type: object
              description: The user who initiated the command.
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
      title: CommandResource
    CommandStatus:
      type: string
      enum:
        - waiting
        - running
        - finished
        - timeout
        - failed
      title: CommandStatus
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