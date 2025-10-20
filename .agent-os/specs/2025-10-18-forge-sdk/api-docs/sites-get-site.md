---
source: https://forge.laravel.com/docs/api-reference/sites/get-site.md
fetched: 2025-10-19T14:41:21.266Z
---

# Get site

> Show the specified site.

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi get /orgs/{organization}/sites/{site}
paths:
  path: /orgs/{organization}/sites/{site}
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
                  - $ref: '#/components/schemas/SiteResource'
            requiredProperties:
              - data
        examples:
          example:
            value:
              data:
                id: <string>
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
        description: '`SiteResource`'
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