---
source: https://forge.laravel.com/docs/api-reference/scheduled-jobs/get-scheduled-job.md
fetched: 2025-10-19T14:39:07.323Z
---

# Get scheduled job

> Show a specific scheduled job.

Processing mode: <small><code>sync</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi get /orgs/{organization}/servers/{server}/scheduled-jobs/{job}
paths:
  path: /orgs/{organization}/servers/{server}/scheduled-jobs/{job}
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
        job:
          schema:
            - type: integer
              required: true
              description: The job ID
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
                  - $ref: '#/components/schemas/JobResource'
            requiredProperties:
              - data
        examples:
          example:
            value:
              data:
                id: <string>
                type: scheduledJobs
                attributes:
                  name: <string>
                  command: <string>
                  status: <string>
                  user: <string>
                  frequency: <string>
                  cron: <string>
                  next_run_time: <string>
                  created_at: '2023-11-07T05:31:56Z'
                  updated_at: '2023-11-07T05:31:56Z'
                links:
                  self:
                    href: <string>
                    rel: <string>
                    describedby: <string>
                    title: <string>
                    type: <string>
                    hreflang: <string>
                    meta: {}
        description: '`JobResource`'
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
    JobResource:
      type: object
      properties:
        id:
          type: string
        type:
          type: string
          enum:
            - scheduledJobs
        attributes:
          type: object
          properties:
            name:
              type:
                - string
                - 'null'
            command:
              type: string
            status:
              type: string
            user:
              type: string
            frequency:
              type: string
            cron:
              type: string
            next_run_time:
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
            - command
            - status
            - user
            - frequency
            - cron
            - next_run_time
            - created_at
            - updated_at
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
      title: JobResource
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