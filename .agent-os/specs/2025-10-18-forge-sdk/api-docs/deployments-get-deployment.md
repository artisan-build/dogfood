---
source: https://forge.laravel.com/docs/api-reference/deployments/get-deployment.md
fetched: 2025-10-19T14:35:41.604Z
---

# Get deployment

> Show a specific deployment.

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi get /orgs/{organization}/servers/{server}/sites/{site}/deployments/{deployment}
paths:
  path: /orgs/{organization}/servers/{server}/sites/{site}/deployments/{deployment}
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
        deployment:
          schema:
            - type: integer
              required: true
              description: The deployment ID
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
                  - $ref: '#/components/schemas/DeploymentResource'
            requiredProperties:
              - data
        examples:
          example:
            value:
              data:
                id: <string>
                type: deployments
                attributes:
                  commit:
                    hash: <string>
                    author: <string>
                    message: <string>
                    branch: <string>
                  type: <string>
                  status: cancelled
                  started_at: '2025-07-29T09:00:00Z'
                  ended_at: '2025-07-29T09:00:00Z'
                  created_at: '2025-07-29T09:00:00Z'
                  updated_at: '2025-07-29T09:00:00Z'
        description: '`DeploymentResource`'
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
    DeploymentResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
          enum:
            - deployments
        attributes:
          type: object
          properties:
            commit:
              type: object
              description: The commit information for the deployment.
              properties:
                hash:
                  type:
                    - string
                    - 'null'
                  description: The commit hash.
                author:
                  type:
                    - string
                    - 'null'
                  description: The commit author.
                message:
                  type:
                    - string
                    - 'null'
                  description: The commit message.
                branch:
                  type:
                    - string
                    - 'null'
                  description: The commit branch.
              required:
                - hash
                - author
                - message
                - branch
            type:
              type: string
            status:
              $ref: '#/components/schemas/DeploymentStatus'
            started_at:
              type: string
              format: date-time
              description: The date and time the deployment started.
              examples:
                - '2025-07-29T09:00:00Z'
            ended_at:
              type: string
              format: date-time
              description: The date and time the deployment ended.
              examples:
                - '2025-07-29T09:00:00Z'
            created_at:
              type: string
              format: date-time
              description: The date and time the deployment was created.
              examples:
                - '2025-07-29T09:00:00Z'
            updated_at:
              type: string
              format: date-time
              description: The date and time the deployment was last updated.
              examples:
                - '2025-07-29T09:00:00Z'
          required:
            - commit
            - type
            - status
            - started_at
            - ended_at
            - created_at
            - updated_at
      required:
        - id
        - type
      title: DeploymentResource
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

````