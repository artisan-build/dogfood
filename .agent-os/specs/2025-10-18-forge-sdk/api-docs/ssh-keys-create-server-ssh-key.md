---
source: https://forge.laravel.com/docs/api-reference/ssh-keys/create-server-ssh-key.md
fetched: 2025-10-19T14:38:51.037Z
---

# Create server SSH key

> Add a new SSH key to the server.

Processing mode: <small><code>async</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi post /orgs/{organization}/servers/{server}/ssh-keys
paths:
  path: /orgs/{organization}/servers/{server}/ssh-keys
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
              name:
                allOf:
                  - type: string
                    description: The name of the SSH key.
                    example: oliver-macbook
              key:
                allOf:
                  - type: string
                    description: The public SSH key.
                    example: ssh-ed25519 AAAAC3NzaC1lZDI1NTE5A... user@example.com
              user:
                allOf:
                  - type:
                      - string
                      - 'null'
                    description: The user associated with the SSH key.
                    example: username
                    default: forge
            required: true
            title: CreateSshKeyRequest
            refIdentifier: '#/components/schemas/CreateSshKeyRequest'
            requiredProperties:
              - name
              - key
        examples:
          example:
            value:
              name: oliver-macbook
              key: ssh-ed25519 AAAAC3NzaC1lZDI1NTE5A... user@example.com
              user: username
  response:
    '202':
      _mintlify/placeholder:
        schemaArray:
          - type: any
            description: Adding SSH key to server accepted
        examples: {}
        description: Adding SSH key to server accepted
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
  schemas: {}

````