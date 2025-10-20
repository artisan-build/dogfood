---
source: https://forge.laravel.com/docs/api-reference/deployments/update-deployment-script.md
fetched: 2025-10-19T14:35:28.898Z
---

# Update deployment script

> 

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi put /orgs/{organization}/servers/{server}/sites/{site}/deployments/script
paths:
  path: /orgs/{organization}/servers/{server}/sites/{site}/deployments/script
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
    body:
      application/json:
        schemaArray:
          - type: object
            properties:
              content:
                allOf:
                  - type: string
              auto_source:
                allOf:
                  - type:
                      - boolean
                      - 'null'
            required: true
            title: UpdateDeploymentScriptRequest
            refIdentifier: '#/components/schemas/UpdateDeploymentScriptRequest'
            requiredProperties:
              - content
        examples:
          example:
            value:
              content: <string>
              auto_source: true
  response:
    '200':
      application/vnd.api+json:
        schemaArray:
          - type: object
            properties:
              data:
                allOf:
                  - $ref: '#/components/schemas/DeploymentScriptResource'
            requiredProperties:
              - data
        examples:
          example:
            value:
              data:
                id: <string>
                type: deploymentScripts
                attributes:
                  content: <string>
                  auto_source: true
                links:
                  self:
                    href: <string>
                    rel: <string>
                    describedby: <string>
                    title: <string>
                    type: <string>
                    hreflang: <string>
                    meta: {}
        description: '`DeploymentScriptResource`'
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
    DeploymentScriptResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
          enum:
            - deploymentScripts
        attributes:
          type: object
          properties:
            content:
              type:
                - string
                - 'null'
              description: The content of the deployment script.
            auto_source:
              type: boolean
              description: Make .env variables available to deployment script
          required:
            - content
            - auto_source
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
      title: DeploymentScriptResource
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