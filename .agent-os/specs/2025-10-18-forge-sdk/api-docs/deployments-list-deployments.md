---
source: https://forge.laravel.com/docs/api-reference/deployments/list-deployments.md
fetched: 2025-10-19T14:35:16.644Z
---

# List deployments

> Show all recent deployments for the site.

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi get /orgs/{organization}/servers/{server}/sites/{site}/deployments
paths:
  path: /orgs/{organization}/servers/{server}/sites/{site}/deployments
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
      query:
        sort:
          schema:
            - type: string
              description: >-
                Available sorts are `created_at`. You can sort by multiple
                options by separating them with a comma. To sort in descending
                order, use `-` sign in front of the sort, for example:
                `-created_at`.
        page[size]:
          schema:
            - type: integer
              description: The number of results that will be returned per page.
              default: 30
        page[cursor]:
          schema:
            - type: string
              description: The cursor to start the pagination from.
        filter[commit_hash]:
          schema:
            - type: string
              description: The commit hash of the deployment.
              examples:
                - 011118f
              example: 011118f
        filter[commit_message]:
          schema:
            - type: string
              description: The commit message of the deployment.
              examples:
                - WIP
              example: WIP
        filter[commit_author]:
          schema:
            - type: string
              description: The commit author of the deployment.
              examples:
                - Taylor Otwell
              example: Taylor Otwell
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
                  - type: array
                    items:
                      $ref: '#/components/schemas/DeploymentResource'
              links:
                allOf:
                  - type: object
                    properties:
                      first:
                        type: string
                      last:
                        type: string
                      prev:
                        type: string
                      next:
                        type: string
              meta:
                allOf:
                  - type: object
                    properties:
                      path:
                        type:
                          - string
                          - 'null'
                        description: Base path for paginator generated URLs.
                      per_page:
                        type: integer
                        description: Number of items shown per page.
                      next_cursor:
                        type:
                          - string
                          - 'null'
                        description: The "cursor" that points to the next set of items.
                      prev_cursor:
                        type:
                          - string
                          - 'null'
                        description: The "cursor" that points to the previous set of items.
                    required:
                      - path
                      - per_page
                      - next_cursor
                      - prev_cursor
            requiredProperties:
              - data
              - links
              - meta
        examples:
          example:
            value:
              data:
                - id: <string>
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
              links:
                first: <string>
                last: <string>
                prev: <string>
                next: <string>
              meta:
                path: <string>
                per_page: 123
                next_cursor: <string>
                prev_cursor: <string>
        description: Paginated set of `DeploymentResource`
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