---
source: https://forge.laravel.com/docs/api-reference/sites/list-sites-for-server.md
fetched: 2025-10-19T14:41:23.978Z
---

# List sites for server

> List all sites associated with the server.

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi get /orgs/{organization}/servers/{server}/sites
paths:
  path: /orgs/{organization}/servers/{server}/sites
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
      query:
        sort:
          schema:
            - type: string
              description: >-
                Available sorts are `name`, `created_at`, `updated_at`. You can
                sort by multiple options by separating them with a comma. To
                sort in descending order, use `-` sign in front of the sort, for
                example: `-name`.
        include:
          schema:
            - type: string
              description: >-
                Available includes are `tags`, `tagsCount`, `tagsExists`,
                `latestDeployment`, `latestDeploymentCount`,
                `latestDeploymentExists`. You can include multiple options by
                separating them with a comma.
        page[size]:
          schema:
            - type: integer
              description: The number of results that will be returned per page.
              default: 30
        page[cursor]:
          schema:
            - type: string
              description: The cursor to start the pagination from.
        filter[name]:
          schema:
            - type: string
              description: The name of the site.
              examples:
                - forge.laravel.com
              example: forge.laravel.com
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
                      $ref: '#/components/schemas/SiteResource'
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
              included:
                allOf:
                  - type: array
                    items:
                      anyOf:
                        - $ref: '#/components/schemas/TagResource'
                        - $ref: '#/components/schemas/DeploymentResource'
            requiredProperties:
              - data
              - links
              - meta
        examples:
          example:
            value:
              data:
                - id: <string>
                  type: sites
                  attributes:
                    name: <string>
                    url: <string>
                    user: <string>
                    https: true
                    web_directory: <string>
                    root_directory: <string>
                    aliases:
                      - <any>
                    php_version: <string>
                    deployment_status: <string>
                    quick_deploy: true
                    isolated: true
                    shared_paths: {}
                    repository:
                      provider: <string>
                      url: <string>
                      branch: <string>
                      status: installed
                    database: <string>
                    maintenance_mode:
                      enabled: true
                      status: disabling
                    zero_downtime_deployments: true
                    deployment_script: <string>
                    wildcards: true
                    app_type: <string>
                    uses_envoyer: true
                    deployment_url: <string>
                    healthcheck_url: <string>
                    created_at: '2023-11-07T05:31:56Z'
                    updated_at: '2023-11-07T05:31:56Z'
                  relationships:
                    tags:
                      data:
                        - type: tags
                          id: <string>
                    latestDeployment:
                      data:
                        type: deployments
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
              included:
                - id: <string>
                  type: tags
                  attributes:
                    name: <string>
                    created_at: '2025-07-29T09:00:00Z'
                    updated_at: '2025-07-30T09:00:00Z'
        description: Paginated set of `SiteResource`
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
    DeploymentResourceIdentifier:
      type: object
      properties:
        type:
          type: string
          enum:
            - deployments
        id:
          type: string
      required:
        - type
        - id
      title: DeploymentResourceIdentifier
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
    RepositoryStatus:
      type: string
      enum:
        - installed
        - installing
        - removing
      title: RepositoryStatus
    SiteResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
          enum:
            - sites
        attributes:
          type: object
          properties:
            name:
              type: string
            url:
              type: string
            user:
              type: string
            https:
              type: boolean
            web_directory:
              type: string
            root_directory:
              type: string
            aliases:
              type: array
              items: {}
            php_version:
              type: string
            deployment_status:
              type: string
            quick_deploy:
              type:
                - boolean
                - 'null'
            isolated:
              type: boolean
            shared_paths:
              type: object
              description: '* The linked directories for the site.'
              additionalProperties:
                type: string
            repository:
              type: object
              properties:
                provider:
                  type: string
                url:
                  type:
                    - string
                    - 'null'
                branch:
                  type:
                    - string
                    - 'null'
                status:
                  anyOf:
                    - $ref: '#/components/schemas/RepositoryStatus'
                    - type: 'null'
              required:
                - provider
                - url
                - branch
                - status
            database:
              type:
                - string
                - 'null'
            maintenance_mode:
              type: object
              properties:
                enabled:
                  type: boolean
                status:
                  anyOf:
                    - $ref: '#/components/schemas/MaintenanceModeStatus'
                    - type: 'null'
              required:
                - enabled
                - status
            zero_downtime_deployments:
              type: boolean
            deployment_script:
              type:
                - string
                - 'null'
            wildcards:
              type:
                - boolean
                - 'null'
            app_type:
              type: string
            uses_envoyer:
              type: boolean
            deployment_url:
              type: string
            healthcheck_url:
              type:
                - string
                - 'null'
            created_at:
              type:
                - string
                - 'null'
              format: date-time
            updated_at:
              type:
                - string
                - 'null'
              format: date-time
          required:
            - name
            - url
            - user
            - https
            - web_directory
            - root_directory
            - aliases
            - php_version
            - deployment_status
            - quick_deploy
            - isolated
            - shared_paths
            - repository
            - database
            - maintenance_mode
            - zero_downtime_deployments
            - deployment_script
            - wildcards
            - app_type
            - uses_envoyer
            - deployment_url
            - healthcheck_url
            - created_at
            - updated_at
        relationships:
          type: object
          properties:
            tags:
              type: object
              properties:
                data:
                  type: array
                  items:
                    $ref: '#/components/schemas/TagResourceIdentifier'
              required:
                - data
            latestDeployment:
              type: object
              properties:
                data:
                  anyOf:
                    - $ref: '#/components/schemas/DeploymentResourceIdentifier'
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
      title: SiteResource
    TagResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
          enum:
            - tags
        attributes:
          type: object
          properties:
            name:
              type: string
            created_at:
              type: string
              format: date-time
              description: The date and time the tag was created.
              examples:
                - '2025-07-29T09:00:00Z'
            updated_at:
              type: string
              format: date-time
              description: The date and time the tag was last updated.
              examples:
                - '2025-07-30T09:00:00Z'
          required:
            - name
            - created_at
            - updated_at
      required:
        - id
        - type
      title: TagResource
    TagResourceIdentifier:
      type: object
      properties:
        type:
          type: string
          enum:
            - tags
        id:
          type: string
      required:
        - type
        - id
      title: TagResourceIdentifier

````