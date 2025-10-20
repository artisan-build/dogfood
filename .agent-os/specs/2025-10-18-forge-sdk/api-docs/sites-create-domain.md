---
source: https://forge.laravel.com/docs/api-reference/sites/create-domain.md
fetched: 2025-10-19T14:41:35.433Z
---

# Create domain

> Add a new domain to the site

Processing mode: <small><code>async</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi post /orgs/{organization}/servers/{server}/sites/{site}/domains
paths:
  path: /orgs/{organization}/servers/{server}/sites/{site}/domains
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
                    description: The name of the domain.
                    example: laravel.com
              allow_wildcard_subdomains:
                allOf:
                  - type: boolean
                    description: Whether to allow wildcard subdomains for the domain.
                    example: false
              www_redirect_type:
                allOf:
                  - description: The type of `www` redirection to apply to the domain.
                    example: from-www
                    $ref: '#/components/schemas/WwwRedirectType'
            required: true
            title: CreateDomainRequest
            refIdentifier: '#/components/schemas/CreateDomainRequest'
            requiredProperties:
              - name
              - allow_wildcard_subdomains
              - www_redirect_type
        examples:
          example:
            value:
              name: laravel.com
              allow_wildcard_subdomains: false
              www_redirect_type: from-www
  response:
    '202':
      application/vnd.api+json:
        schemaArray:
          - type: object
            properties:
              data:
                allOf:
                  - $ref: '#/components/schemas/DomainRecordResource'
            requiredProperties:
              - data
        examples:
          example:
            value:
              data:
                id: <string>
                type: domainRecords
                attributes:
                  name: forge.laravel.com
                  type: primary
                  status: enabled
                  www_redirect_type: from-www
                  allow_wildcard_subdomains: true
                  created_at: '2025-07-29T09:00:00Z'
                  updated_at: '2025-07-30T09:00:00Z'
                links:
                  self:
                    href: <string>
                    rel: <string>
                    describedby: <string>
                    title: <string>
                    type: <string>
                    hreflang: <string>
                    meta: {}
        description: '`DomainRecordResource`'
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
    DomainRecordResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
          enum:
            - domainRecords
        attributes:
          type: object
          properties:
            name:
              type: string
              description: The name of the domain.
              examples:
                - forge.laravel.com
            type:
              type: string
              description: The type of domain.
              enum:
                - primary
                - alias
              examples:
                - primary
            status:
              description: The status of the domain.
              examples:
                - enabled
              $ref: '#/components/schemas/DomainRecordStatus'
            www_redirect_type:
              description: The type of `www.` redirection for the domain.
              examples:
                - from-www
              $ref: '#/components/schemas/WwwRedirectType'
            allow_wildcard_subdomains:
              type: boolean
              description: Whether the domain allows wildcard subdomains.
              examples:
                - true
            created_at:
              type: string
              format: date-time
              description: The date and time the domain was created.
              examples:
                - '2025-07-29T09:00:00Z'
            updated_at:
              type: string
              format: date-time
              description: The date and time the domain was last updated.
              examples:
                - '2025-07-30T09:00:00Z'
          required:
            - name
            - type
            - status
            - www_redirect_type
            - allow_wildcard_subdomains
            - created_at
            - updated_at
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
      title: DomainRecordResource
    DomainRecordStatus:
      type: string
      enum:
        - pending
        - connecting
        - enabled
        - removing
        - securing
        - updating
        - disabling
        - disabled
        - enabling
      title: DomainRecordStatus
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
    WwwRedirectType:
      type: string
      enum:
        - from-www
        - to-www
        - none
      title: WwwRedirectType

````