---
source: https://forge.laravel.com/docs/api-reference/sites/get-composer-credentials-for-the-site.md
fetched: 2025-10-19T14:42:09.632Z
---

# Get composer credentials for the site

> 

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi get /orgs/{organization}/servers/{server}/sites/{site}/composer/credentials
paths:
  path: /orgs/{organization}/servers/{server}/sites/{site}/composer/credentials
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
                  - type: array
                    items:
                      $ref: '#/components/schemas/ComposerCredentialResource'
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
                      current_page:
                        type: integer
                      from:
                        type:
                          - integer
                          - 'null'
                      path:
                        type:
                          - string
                          - 'null'
                        description: Base path for paginator generated URLs.
                      per_page:
                        type: integer
                        description: Number of items shown per page.
                      to:
                        type:
                          - integer
                          - 'null'
                        description: Number of the last item in the slice.
                    required:
                      - current_page
                      - from
                      - path
                      - per_page
                      - to
            requiredProperties:
              - data
              - links
              - meta
        examples:
          example:
            value:
              data:
                - id: <string>
                  type: <string>
                  attributes:
                    repository: <string>
                    username: <string>
                    password: <string>
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
                current_page: 123
                from: 123
                path: <string>
                per_page: 123
                to: 123
        description: Paginated set of `ComposerCredentialResource`
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
    ComposerCredentialResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
        attributes:
          type: object
          properties:
            repository:
              type: string
            username:
              type: string
            password:
              type: string
          required:
            - repository
            - username
            - password
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
      title: ComposerCredentialResource
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