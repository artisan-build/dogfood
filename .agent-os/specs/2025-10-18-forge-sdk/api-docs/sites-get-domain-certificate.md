---
source: https://forge.laravel.com/docs/api-reference/sites/get-domain-certificate.md
fetched: 2025-10-19T14:41:48.958Z
---

# Get domain certificate

> Get the certificate for a given domain.

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi get /orgs/{organization}/servers/{server}/sites/{site}/domains/{domainRecord}/certificate
paths:
  path: >-
    /orgs/{organization}/servers/{server}/sites/{site}/domains/{domainRecord}/certificate
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
                  - $ref: '#/components/schemas/CertificateResource'
            requiredProperties:
              - data
        examples:
          example:
            value:
              data:
                id: <string>
                type: certificates
                attributes:
                  type: letsencrypt
                  verification_method: http-01
                  key_type: ecdsa
                  preferred_chain: ISRG Root X1
                  request_status: '''creating'''
                  status: '''installed'''
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
        description: '`CertificateResource`'
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
    CertificateKeyType:
      type: string
      enum:
        - ecdsa
        - rsa
      title: CertificateKeyType
    CertificateRequestStatus:
      type: string
      enum:
        - verifying
        - creating
        - created
      title: CertificateRequestStatus
    CertificateResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
          enum:
            - certificates
        attributes:
          type: object
          properties:
            type:
              description: The type of certificate.
              examples:
                - letsencrypt
              $ref: '#/components/schemas/CertificateType'
            verification_method:
              anyOf:
                - description: The verification method for Let's Encrypt certificates.
                  examples:
                    - http-01
                  $ref: '#/components/schemas/CertificateVerificationMethod'
                - type: 'null'
            key_type:
              anyOf:
                - description: The key type for Let's Encrypt certificates.
                  examples:
                    - ecdsa
                  $ref: '#/components/schemas/CertificateKeyType'
                - type: 'null'
            preferred_chain:
              type:
                - string
                - 'null'
              description: The preferred chain for Let's Encrypt certificates.
              enum:
                - ISRG Root X1
              examples:
                - ISRG Root X1
            request_status:
              description: The certificate request status.
              examples:
                - '''creating'''
              $ref: '#/components/schemas/CertificateRequestStatus'
            status:
              description: The status of the certificate.
              examples:
                - '''installed'''
              $ref: '#/components/schemas/ResourceState'
            created_at:
              type: string
              format: date-time
              description: The date and time the certificate was created.
              examples:
                - '2025-07-29T09:00:00Z'
            updated_at:
              type: string
              format: date-time
              description: The date and time the certificate was last updated.
              examples:
                - '2025-07-30T09:00:00Z'
          required:
            - type
            - verification_method
            - key_type
            - preferred_chain
            - request_status
            - status
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
      title: CertificateResource
    CertificateType:
      type: string
      enum:
        - letsencrypt
        - csr
        - existing
      title: CertificateType
    CertificateVerificationMethod:
      type: string
      enum:
        - http-01
        - dns-01
      title: CertificateVerificationMethod
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
    ResourceState:
      type: string
      enum:
        - installing
        - installed
        - removing
        - restarting
        - stopping
        - stopped
        - starting
        - syncing
        - updating
        - disabling
        - disabled
        - enabling
        - running
        - restoring
        - deleting
        - failed
        - success
        - failed-unknown
        - failed-runner
      title: ResourceState

````