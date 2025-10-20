---
source: https://forge.laravel.com/docs/api-reference/deployments/get-deployment-status.md
fetched: 2025-10-19T14:35:21.702Z
---

# Get deployment status

> 

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi get /orgs/{organization}/servers/{server}/sites/{site}/deployments/status
paths:
  path: /orgs/{organization}/servers/{server}/sites/{site}/deployments/status
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
                  - $ref: '#/components/schemas/DeploymentStatusResource'
            requiredProperties:
              - data
        examples:
          example:
            value:
              data:
                id: <string>
                type: deploymentStatuses
                attributes:
                  status: cancelled
                  started_at: '2025-07-29T09:00:00Z'
                links:
                  self:
                    href: <string>
                    rel: <string>
                    describedby: <string>
                    title: <string>
                    type: <string>
                    hreflang: <string>
                    meta: {}
        description: '`DeploymentStatusResource`'
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
    DeploymentStatus:
      type: string
      enum:
        - cancelled
        - deploying
        - failed
        - failed-build
        - finished
        - pending
        - queued
      title: DeploymentStatus
    DeploymentStatusResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
          enum:
            - deploymentStatuses
        attributes:
          type: object
          properties:
            status:
              anyOf:
                - $ref: '#/components/schemas/DeploymentStatus'
                - type: 'null'
            started_at:
              type: string
              format: date-time
              description: The time the deployment started.
              examples:
                - '2025-07-29T09:00:00Z'
          required:
            - status
            - started_at
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
      title: DeploymentStatusResource
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