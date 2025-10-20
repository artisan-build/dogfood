---
source: https://forge.laravel.com/docs/api-reference/sites/create-domain-action.md
fetched: 2025-10-19T14:41:46.897Z
---

# Create domain action

> Run an action on a domain, defined by the action type parameter.

Processing mode: <small><code>async</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi post /orgs/{organization}/servers/{server}/sites/{site}/domains/{domainRecord}/actions
paths:
  path: >-
    /orgs/{organization}/servers/{server}/sites/{site}/domains/{domainRecord}/actions
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
        site:
          schema:
            - type: integer
              required: true
              description: The site ID
        domainRecord:
          schema:
            - type: integer
              required: true
              description: The domain record ID
      query: {}
      header: {}
      cookie: {}
    body:
      application/json:
        schemaArray:
          - type: object
            properties:
              action:
                allOf:
                  - description: The action to perform on the domain.
                    example: '''enable'''
                    $ref: '#/components/schemas/DomainRecordAction'
            required: true
            title: DomainActionRequest
            refIdentifier: '#/components/schemas/DomainActionRequest'
            requiredProperties:
              - action
        examples:
          example:
            value:
              action: '''enable'''
  response:
    '202': {}
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
    DomainRecordAction:
      type: string
      enum:
        - enable
        - disable
        - mark-as-primary
      title: DomainRecordAction

````