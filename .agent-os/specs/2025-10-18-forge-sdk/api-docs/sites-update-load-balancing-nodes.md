---
source: https://forge.laravel.com/docs/api-reference/sites/update-load-balancing-nodes.md
fetched: 2025-10-19T14:42:24.097Z
---

# Update load balancing nodes

> 

Processing mode: <small><code>async</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi put /orgs/{organization}/servers/{server}/sites/{site}/load-balancing-nodes
paths:
  path: /orgs/{organization}/servers/{server}/sites/{site}/load-balancing-nodes
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
              balancer_method:
                allOf:
                  - $ref: '#/components/schemas/NodeBalancerMethod'
              balancing:
                allOf:
                  - type: array
                    items:
                      type: object
                      properties:
                        server_id:
                          type: integer
                        port:
                          type:
                            - integer
                            - 'null'
                          minimum: 1
                          maximum: 65535
                        weight:
                          type: integer
                          minimum: 1
                        backup:
                          type: boolean
                        down:
                          type: boolean
                      required:
                        - server_id
                        - weight
                    minItems: 1
            required: true
            title: UpdateLoadBalancerRequest
            refIdentifier: '#/components/schemas/UpdateLoadBalancerRequest'
            requiredProperties:
              - balancer_method
              - balancing
        examples:
          example:
            value:
              balancer_method: round_robin
              balancing:
                - server_id: 123
                  port: 32768
                  weight: 2
                  backup: true
                  down: true
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
    NodeBalancerMethod:
      type: string
      enum:
        - round_robin
        - least_conn
        - ip_hash
      title: NodeBalancerMethod

````