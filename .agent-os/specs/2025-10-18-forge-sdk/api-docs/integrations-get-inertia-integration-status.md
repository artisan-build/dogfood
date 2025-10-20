---
source: https://forge.laravel.com/docs/api-reference/integrations/get-inertia-integration-status.md
fetched: 2025-10-19T14:36:15.922Z
---

# Get Inertia integration status

> Show whether Inertia SSR integration is enabled.

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi get /orgs/{organization}/servers/{server}/sites/{site}/integrations/inertia
paths:
  path: /orgs/{organization}/servers/{server}/sites/{site}/integrations/inertia
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
                  - $ref: '#/components/schemas/InertiaIntegrationResource'
            requiredProperties:
              - data
        examples:
          example:
            value:
              data:
                id: <string>
                type: inertiaIntegrations
                attributes:
                  enabled: <string>
                  inertia_installed: true
                relationships:
                  backgroundProcess:
                    data:
                      type: backgroundProcesses
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
        description: '`InertiaIntegrationResource`'
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
    BackgroundProcessResourceIdentifier:
      type: object
      properties:
        type:
          type: string
          enum:
            - backgroundProcesses
        id:
          type: string
      required:
        - type
        - id
      title: BackgroundProcessResourceIdentifier
    InertiaIntegrationResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
          enum:
            - inertiaIntegrations
        attributes:
          type: object
          properties:
            enabled:
              type: string
            inertia_installed:
              type: boolean
          required:
            - enabled
            - inertia_installed
        relationships:
          type: object
          properties:
            backgroundProcess:
              type: object
              properties:
                data:
                  anyOf:
                    - $ref: '#/components/schemas/BackgroundProcessResourceIdentifier'
                    - type: 'null'
              required:
                - data
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
      title: InertiaIntegrationResource
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