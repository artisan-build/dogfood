---
source: https://forge.laravel.com/docs/api-reference/roles/update-role.md
fetched: 2025-10-19T14:38:42.833Z
---

# Update role

> Update a role for the organization.

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi put /orgs/{organization}/roles/{role}
paths:
  path: /orgs/{organization}/roles/{role}
  method: put
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
        role:
          schema:
            - type: integer
              required: true
              description: The role ID
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
                    maxLength: 50
              permissions:
                allOf:
                  - type: array
                    items:
                      $ref: '#/components/schemas/Permission'
              description:
                allOf:
                  - type: string
            title: UpdateRoleRequest
            refIdentifier: '#/components/schemas/UpdateRoleRequest'
            requiredProperties:
              - name
        examples:
          example:
            value:
              name: <string>
              permissions:
                - organization:view
              description: <string>
  response:
    '200':
      application/vnd.api+json:
        schemaArray:
          - type: object
            properties:
              data:
                allOf:
                  - $ref: '#/components/schemas/CustomRoleResource'
            requiredProperties:
              - data
        examples:
          example:
            value:
              data:
                id: <string>
                type: customRoles
                attributes:
                  name: <string>
                  created_at: '2023-11-07T05:31:56Z'
                  updated_at: '2023-11-07T05:31:56Z'
                relationships:
                  permissions:
                    data:
                      - type: permissions
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
        description: '`CustomRoleResource`'
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
    CustomRoleResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
          enum:
            - customRoles
        attributes:
          type: object
          properties:
            name:
              type: string
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
            - created_at
            - updated_at
        relationships:
          type: object
          properties:
            permissions:
              type: object
              properties:
                data:
                  type: array
                  items:
                    $ref: '#/components/schemas/PermissionResourceIdentifier'
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
      title: CustomRoleResource
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
    Permission:
      type: string
      enum:
        - organization:view
        - organization:manage
        - organization:delete
        - server:view
        - server:create
        - server:delete
        - server:archive
        - server:transfer
        - server:manage-meta
        - server:manage-packages
        - server:manage-php
        - server:manage-logs
        - server:manage-network
        - server:manage-nginx-templates
        - server:manage-services
        - server:create-keys
        - server:delete-keys
        - server:create-monitors
        - server:delete-monitors
        - server:create-databases
        - server:delete-databases
        - server:create-backups
        - server:delete-backups
        - server:create-daemons
        - server:delete-daemons
        - server:create-schedulers
        - server:delete-schedulers
        - server:web-terminal
        - site:create
        - site:delete
        - site:meta
        - site:manage-commands
        - site:manage-deploys
        - site:manage-nginx
        - site:manage-project
        - site:manage-environment
        - site:manage-notifications
        - site:manage-queues
        - site:manage-redirects
        - site:manage-security
        - site:manage-ssl
        - site:manage-integrations
        - site:manage-heartbeats
        - credential:view
        - credential:manage
        - team:view
        - team:create
        - team:delete
        - recipe:view
        - recipe:manage
        - billing:manage
        - integrations:manage
        - user:view
      title: Permission
    PermissionResourceIdentifier:
      type: object
      properties:
        type:
          type: string
          enum:
            - permissions
        id:
          type: string
      required:
        - type
        - id
      title: PermissionResourceIdentifier

````