---
source: https://forge.laravel.com/docs/api-reference/sites/get-domain-dns-configuration.md
fetched: 2025-10-19T14:41:44.437Z
---

# Get domain DNS configuration

> Show the DNS configuration instructions for a domain.

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi get /orgs/{organization}/servers/{server}/sites/{site}/domains/{domainRecord}/configurations
paths:
  path: >-
    /orgs/{organization}/servers/{server}/sites/{site}/domains/{domainRecord}/configurations
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
        domainRecord:
          schema:
            - type: integer
              required: true
              description: The domain record ID
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
                      $ref: '#/components/schemas/DomainRecordConfigurationResource'
            requiredProperties:
              - data
        examples:
          example:
            value:
              data:
                - id: <string>
                  type: <string>
                  attributes:
                    type: A
                    name: example.com
                    value: 192.168.0.1
                    ttl: 3600
        description: Array of `DomainRecordConfigurationResource`
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
    DomainRecordConfigurationResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
        attributes:
          type: object
          properties:
            type:
              type: string
              description: The type of DNS record.
              enum:
                - A
                - CNAME
                - TXT
              examples:
                - A
            name:
              type: string
              description: The name of the DNS record.
              examples:
                - example.com
            value:
              type: string
              description: >-
                The value (IP address, CNAME target, TXT value) of the DNS
                record.
              examples:
                - 192.168.0.1
            ttl:
              type:
                - integer
                - 'null'
              description: >-
                The recommended TTL (Time to Live) for the DNS record, in
                seconds.
              examples:
                - 3600
          required:
            - type
            - name
            - value
            - ttl
      required:
        - id
        - type
      title: DomainRecordConfigurationResource

````