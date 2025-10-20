---
source: https://forge.laravel.com/docs/api-reference/sites/create-domain-certificate.md
fetched: 2025-10-19T14:41:51.455Z
---

# Create domain certificate

> Create a new certificate for a given domain.

Processing mode: <small><code>async</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi post /orgs/{organization}/servers/{server}/sites/{site}/domains/{domainRecord}/certificate
paths:
  path: >-
    /orgs/{organization}/servers/{server}/sites/{site}/domains/{domainRecord}/certificate
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
              type:
                allOf:
                  - description: The type of certificate to create.
                    example: letsencrypt
                    $ref: '#/components/schemas/CertificateType'
              letsencrypt:
                allOf:
                  - type: object
                    description: The configuration for a Let's Encrypt certificate.
                    properties:
                      verification_method:
                        description: >-
                          The verification method to use for the Let's Encrypt
                          certificate.
                        example: dns-01
                        $ref: '#/components/schemas/CertificateVerificationMethod'
                      key_type:
                        description: >-
                          The type of key to use for the Let's Encrypt
                          certificate.
                        example: ecdsa
                        $ref: '#/components/schemas/CertificateKeyType'
                      preferred_chain:
                        type:
                          - string
                          - 'null'
                        description: The preferred chain for the Let's Encrypt certificate.
                        enum:
                          - ISRG Root X1
                        example: ISRG Root X1
              existing:
                allOf:
                  - type: object
                    description: The configuration for an existing certificate.
                    properties:
                      key:
                        type: string
                        description: The private key for an existing certificate.
                      certificate:
                        type: string
                        description: The certificate chain for an existing certificate.
              csr:
                allOf:
                  - type: object
                    description: The configuration for a CSR (Certificate Signing Request).
                    properties:
                      domain:
                        type: string
                        description: The domain to generate a CSR for.
                      sans:
                        type:
                          - string
                          - 'null'
                        description: The SANs for the CSR, comma-separated.
                      country:
                        type: string
                        description: The country for the CSR.
                      state:
                        type: string
                        description: The state for the CSR.
                      city:
                        type: string
                        description: The city for the CSR.
                      organization:
                        type: string
                        description: The organization for the CSR.
                      department:
                        type: string
                        description: The department for the CSR.
            required: true
            title: CreateDomainCertificateRequest
            refIdentifier: '#/components/schemas/CreateDomainCertificateRequest'
            requiredProperties:
              - type
        examples:
          example:
            value:
              type: letsencrypt
              letsencrypt:
                verification_method: dns-01
                key_type: ecdsa
                preferred_chain: ISRG Root X1
              existing:
                key: <string>
                certificate: <string>
              csr:
                domain: <string>
                sans: <string>
                country: <string>
                state: <string>
                city: <string>
                organization: <string>
                department: <string>
  response:
    '202':
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