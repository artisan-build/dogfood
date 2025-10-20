---
source: https://forge.laravel.com/docs/api-reference/integrations/get-laravel-maintenance-integration-status.md
fetched: 2025-10-19T14:36:27.928Z
---

# Get Laravel Maintenance integration status

> Show whether Laravel Maintenance integration is enabled.

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi get /orgs/{organization}/servers/{server}/sites/{site}/integrations/laravel-maintenance
paths:
  path: >-
    /orgs/{organization}/servers/{server}/sites/{site}/integrations/laravel-maintenance
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
                  - $ref: '#/components/schemas/LaravelMaintenanceIntegrationResource'
            requiredProperties:
              - data
        examples:
          example:
            value:
              data:
                id: <string>
                type: laravelMaintenanceIntegrations
                attributes:
                  enabled: true
                  status: enabling
                  laravel_installed: true
                links:
                  self:
                    href: <string>
                    rel: <string>
                    describedby: <string>
                    title: <string>
                    type: <string>
                    hreflang: <string>
                    meta: {}
        description: '`LaravelMaintenanceIntegrationResource`'
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
    LaravelMaintenanceIntegrationResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
          enum:
            - laravelMaintenanceIntegrations
        attributes:
          type: object
          properties:
            enabled:
              type: boolean
              description: Whether the maintenance mode integration is enabled.
            status:
              anyOf:
                - description: The status of the maintenance mode integration.
                  examples:
                    - enabling
                  $ref: '#/components/schemas/MaintenanceModeStatus'
                - type: 'null'
            laravel_installed:
              type: boolean
              description: Whether Laravel is installed on the site.
          required:
            - enabled
            - status
            - laravel_installed
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
      title: LaravelMaintenanceIntegrationResource
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
    MaintenanceModeStatus:
      type: string
      enum:
        - disabling
        - enabling
      title: MaintenanceModeStatus

````