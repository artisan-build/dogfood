---
source: https://forge.laravel.com/docs/api-reference/integrations/get-laravel-scheduler-integration-job.md
fetched: 2025-10-19T14:36:34.815Z
---

# Get Laravel Scheduler integration job

> Show whether Laravel Scheduler integration is enabled.

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi get /orgs/{organization}/servers/{server}/sites/{site}/integrations/laravel-scheduler
paths:
  path: >-
    /orgs/{organization}/servers/{server}/sites/{site}/integrations/laravel-scheduler
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
                  - $ref: '#/components/schemas/LaravelSchedulerIntegrationResource'
            requiredProperties:
              - data
        examples:
          example:
            value:
              data:
                id: <string>
                type: laravelSchedulerIntegrations
                attributes:
                  enabled: true
                  laravel_installed: <string>
                relationships:
                  job:
                    data:
                      type: scheduledJobs
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
        description: '`LaravelSchedulerIntegrationResource`'
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
    JobResourceIdentifier:
      type: object
      properties:
        type:
          type: string
          enum:
            - scheduledJobs
        id:
          type: string
      required:
        - type
        - id
      title: JobResourceIdentifier
    LaravelSchedulerIntegrationResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
          enum:
            - laravelSchedulerIntegrations
        attributes:
          type: object
          properties:
            enabled:
              type: boolean
            laravel_installed:
              type: string
          required:
            - enabled
            - laravel_installed
        relationships:
          type: object
          properties:
            job:
              type: object
              properties:
                data:
                  anyOf:
                    - $ref: '#/components/schemas/JobResourceIdentifier'
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
      title: LaravelSchedulerIntegrationResource
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