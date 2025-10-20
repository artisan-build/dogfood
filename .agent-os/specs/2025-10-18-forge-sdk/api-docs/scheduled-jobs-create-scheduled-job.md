---
source: https://forge.laravel.com/docs/api-reference/scheduled-jobs/create-scheduled-job.md
fetched: 2025-10-19T14:39:04.846Z
---

# Create scheduled job

> Create a specific scheduled job.

Processing mode: <small><code>async</code></small>

## OpenAPI

````yaml https://forge.laravel.com/api/docs.openapi post /orgs/{organization}/servers/{server}/scheduled-jobs
paths:
  path: /orgs/{organization}/servers/{server}/scheduled-jobs
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
                  - type:
                      - string
                      - 'null'
                    description: The name of the command.
                    example: My scheduled job
              command:
                allOf:
                  - type: string
                    description: The command to run.
                    example: echo $(whoami)
              user:
                allOf:
                  - type: string
                    description: The user to run the scheduled job as.
                    example: root
              frequency:
                allOf:
                  - description: The frequency of the scheduled job.
                    example: hourly
                    $ref: '#/components/schemas/CronFrequency'
              cron:
                allOf:
                  - type:
                      - string
                      - 'null'
                    description: >-
                      The cron expression to use for the scheduled job. Only
                      used if frequency is set to Custom.
                    example: 0 * * * *
              heartbeat:
                allOf:
                  - type:
                      - boolean
                      - 'null'
                    description: >-
                      Whether a heartbeat should be created for the scheduled
                      job.
                    example: true
              grace_period:
                allOf:
                  - type: string
                    description: The grace period, in minutes, for the heartbeat.
                    example: '5'
            required: true
            title: CreateScheduledJobRequest
            refIdentifier: '#/components/schemas/CreateScheduledJobRequest'
            requiredProperties:
              - command
              - user
              - frequency
        examples:
          example:
            value:
              name: My scheduled job
              command: echo $(whoami)
              user: root
              frequency: hourly
              cron: 0 * * * *
              heartbeat: true
              grace_period: '5'
  response:
    '202':
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
    CronFrequency:
      type: string
      enum:
        - minutely
        - hourly
        - nightly
        - weekly
        - monthly
        - reboot
        - custom
      title: CronFrequency
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