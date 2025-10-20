---
source: https://forge.laravel.com/docs/api-reference/servers/create-server.md
fetched: 2025-10-19T14:39:34.931Z
---

# Create server

> Create a new server in the organization. Supports both standard cloud providers
and custom VPS configurations.

Processing mode: <small><code>async</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi post /orgs/{organization}/servers
paths:
  path: /orgs/{organization}/servers
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
      query: {}
      header: {}
      cookie: {}
    body:
      application/json:
        schemaArray:
          - type: object
            properties:
              name:
                allOf:
                  - type: string
                    maxLength: 30
              provider:
                allOf:
                  - type: string
              credential_id:
                allOf:
                  - type: string
              team_id:
                allOf:
                  - type:
                      - integer
                      - 'null'
              type:
                allOf:
                  - $ref: '#/components/schemas/ServerType'
              ubuntu_version:
                allOf:
                  - type: string
                    enum:
                      - '22.04'
                      - '24.04'
              php_version:
                allOf:
                  - type: string
              database_type:
                allOf:
                  - type: string
              recipe_id:
                allOf:
                  - type:
                      - integer
                      - 'null'
              aws:
                allOf:
                  - type: object
                    properties:
                      region_id:
                        type: string
                      size_id:
                        type: string
                      vpc_uuid:
                        type: string
                      subnet_uuid:
                        type: string
                      disk_size:
                        type: string
              ocean2:
                allOf:
                  - type: object
                    properties:
                      region_id:
                        type: string
                      size_id:
                        type: string
                      vpc_uuid:
                        type: string
                      enable_weekly_backups:
                        type: string
              hetzner:
                allOf:
                  - type: object
                    properties:
                      region_id:
                        type: string
                      size_id:
                        type: string
                      network_id:
                        type: string
                      enable_daily_backups:
                        type: string
                  - type: object
                    properties:
                      enable_weekly_backups:
                        type: boolean
                        default: false
              vultr:
                allOf:
                  - type: object
                    properties:
                      region_id:
                        type: string
                      size_id:
                        type: string
                      network_id:
                        type: string
              akamai:
                allOf:
                  - type: object
                    properties:
                      region_id:
                        type: string
                      size_id:
                        type: string
              laravel:
                allOf:
                  - type: object
                    properties:
                      region_id:
                        type: string
                      size_id:
                        type: string
                  - type: object
                    properties:
                      vpc_uuid:
                        type: string
              custom:
                allOf:
                  - type: object
                    properties:
                      ip_address:
                        type: string
                      private_ip_address:
                        type: string
                      ssh_port:
                        type: string
                      behind_nat:
                        type: string
                      nat_ssh_port:
                        type: string
              add_key_to_source_control:
                allOf:
                  - type: boolean
                    default: true
              database:
                allOf:
                  - type: string
            title: CreateServerRequest
            refIdentifier: '#/components/schemas/CreateServerRequest'
            requiredProperties:
              - name
              - provider
              - type
              - ubuntu_version
        examples:
          example:
            value:
              name: <string>
              provider: <string>
              credential_id: <string>
              team_id: 123
              type: app
              ubuntu_version: '22.04'
              php_version: <string>
              database_type: <string>
              recipe_id: 123
              aws:
                region_id: <string>
                size_id: <string>
                vpc_uuid: <string>
                subnet_uuid: <string>
                disk_size: <string>
              ocean2:
                region_id: <string>
                size_id: <string>
                vpc_uuid: <string>
                enable_weekly_backups: <string>
              hetzner:
                region_id: <string>
                size_id: <string>
                network_id: <string>
                enable_daily_backups: <string>
                enable_weekly_backups: false
              vultr:
                region_id: <string>
                size_id: <string>
                network_id: <string>
              akamai:
                region_id: <string>
                size_id: <string>
              laravel:
                region_id: <string>
                size_id: <string>
                vpc_uuid: <string>
              custom:
                ip_address: <string>
                private_ip_address: <string>
                ssh_port: <string>
                behind_nat: <string>
                nat_ssh_port: <string>
              add_key_to_source_control: true
              database: <string>
  response:
    '200':
      application/json:
        schemaArray:
          - type: object
            properties: {}
        examples:
          example:
            value: {}
        description: ''
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
    ServerType:
      type: string
      enum:
        - app
        - web
        - loadbalancer
        - database
        - cache
        - worker
        - meilisearch
      title: ServerType

````