---
source: https://forge.laravel.com/docs/api-reference/sites/create-site.md
fetched: 2025-10-19T14:41:26.020Z
---

# Create site

> Add a new site to the server.

Processing mode: <small><code>async</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi post /orgs/{organization}/servers/{server}/sites
paths:
  path: /orgs/{organization}/servers/{server}/sites
  method: post
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
      query: {}
      header: {}
      cookie: {}
    body:
      application/json:
        schemaArray:
          - type: object
            properties:
              type:
                allOf:
                  - $ref: '#/components/schemas/SiteType'
              domain_mode:
                allOf:
                  - anyOf:
                      - type: string
                      - $ref: '#/components/schemas/CreateSiteDomainMode'
              name:
                allOf:
                  - type: string
              www_redirect_type:
                allOf:
                  - type: string
              allow_wildcard_subdomains:
                allOf:
                  - type: string
              web_directory:
                allOf:
                  - type:
                      - string
                      - 'null'
              is_isolated:
                allOf:
                  - type: boolean
              isolated_user:
                allOf:
                  - type: string
              php_version:
                allOf:
                  - $ref: '#/components/schemas/PhpVersion'
              zero_downtime_deployments:
                allOf:
                  - type: boolean
              nginx_template_id:
                allOf:
                  - type: integer
              source_control_provider:
                allOf:
                  - $ref: '#/components/schemas/SourceControlProvider'
              repository:
                allOf:
                  - type:
                      - string
                      - 'null'
              branch:
                allOf:
                  - type:
                      - string
                      - 'null'
              database_id:
                allOf:
                  - type:
                      - integer
                      - 'null'
              database_user_id:
                allOf:
                  - type: string
              statamic_setup:
                allOf:
                  - type: string
                    description: The type of setup for Statmic apps.
              statamic_starter_kit:
                allOf:
                  - type: string
                    description: The starter kit for the Statamic app.
              statamic_super_user_email:
                allOf:
                  - type: string
              statamic_super_user_password:
                allOf:
                  - type: string
              install_composer_dependencies:
                allOf:
                  - type: boolean
              generate_deploy_key:
                allOf:
                  - type: boolean
              public_deploy_key:
                allOf:
                  - type: string
              private_deploy_key:
                allOf:
                  - type: string
              nuxt_next_mode:
                allOf:
                  - type: string
                    description: The render mode for Next/Nuxt applications.
              nuxt_next_build_command:
                allOf:
                  - type: string
                    description: The build command for Next/Nuxt applications.
              nuxt_next_port:
                allOf:
                  - type: integer
                    description: The port used for Next/Nuxt applications.
              push_to_deploy:
                allOf:
                  - type: boolean
                    description: >-
                      Automatically trigger a new deployment when changes are
                      pushed to the environment's Git branch.
                    default: false
              shared_paths:
                allOf:
                  - type: array
                    description: >-
                      A list of files or directories to be shared between
                      releases for zero-downtime deployments.
                    items:
                      type: object
                      properties:
                        from:
                          type: string
                          description: >-
                            The path relative to the project's root directory on
                            the server that should be shared between releases.
                        to:
                          type: string
                          description: >-
                            The path relative to the deployment's release
                            directory that the shared path should be linked to.
                      required:
                        - from
                        - to
            required: true
            title: CreateSiteRequest
            refIdentifier: '#/components/schemas/CreateSiteRequest'
            requiredProperties:
              - type
        examples:
          example:
            value:
              type: laravel
              domain_mode: <string>
              name: <string>
              www_redirect_type: <string>
              allow_wildcard_subdomains: <string>
              web_directory: <string>
              is_isolated: true
              isolated_user: <string>
              php_version: php5
              zero_downtime_deployments: true
              nginx_template_id: 123
              source_control_provider: github
              repository: <string>
              branch: <string>
              database_id: 123
              database_user_id: <string>
              statamic_setup: <string>
              statamic_starter_kit: <string>
              statamic_super_user_email: <string>
              statamic_super_user_password: <string>
              install_composer_dependencies: true
              generate_deploy_key: true
              public_deploy_key: <string>
              private_deploy_key: <string>
              nuxt_next_mode: <string>
              nuxt_next_build_command: <string>
              nuxt_next_port: 123
              push_to_deploy: false
              shared_paths:
                - from: <string>
                  to: <string>
  response:
    '202':
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
    '422':
      application/json:
        schemaArray:
          - type: object
            properties:
              message:
                allOf:
                  - type: string
                    description: Errors overview.
              errors:
                allOf:
                  - type: object
                    description: >-
                      A detailed description of each field that failed
                      validation.
                    additionalProperties:
                      type: array
                      items:
                        type: string
            requiredProperties:
              - message
              - errors
        examples:
          example:
            value:
              message: <string>
              errors: {}
        description: Validation error
  deprecated: false
  type: path
  xMint:
    metadata:
      noindex: true
components:
  schemas:
    CreateSiteDomainMode:
      type: string
      enum:
        - on-forge
        - custom
      title: CreateSiteDomainMode
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
    PhpVersion:
      type: string
      enum:
        - php5
        - php56-old
        - php56
        - php70
        - php71
        - php72
        - php73
        - php74
        - php80
        - php81
        - php82
        - php83
        - php84
        - php85
      title: PhpVersion
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
    SiteType:
      type: string
      enum:
        - laravel
        - symfony
        - statamic
        - wordpress
        - phpmyadmin
        - php
        - next.js
        - nuxt.js
        - static-html
        - other
        - custom
      title: SiteType
    SourceControlProvider:
      type: string
      description: |
        All supported source control providers.
      enum:
        - github
        - gitlab
        - bitbucket
        - gitlab-custom
        - custom
      title: SourceControlProvider
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