# Spec Tasks

These are the tasks to be completed for the spec detailed in @.agent-os/specs/2025-10-18-forge-sdk/spec.md

> Created: 2025-10-18
> Status: Ready for Implementation

## Tasks

- [ ] 1. Package Foundation & Setup
  - [ ] 1.1 Write tests for ForgeConnector class
  - [ ] 1.2 Update config/forge-sdk.php with API token, base URL, timeout, and retry configuration
  - [ ] 1.3 Create ForgeConnector class extending Saloon\Http\Connector
  - [ ] 1.4 Implement Bearer token authentication in connector using Saloon's auth mechanisms
  - [ ] 1.5 Configure base URL from config (https://forge.laravel.com/api)
  - [ ] 1.6 Configure default headers (Accept: application/json, Content-Type: application/json)
  - [ ] 1.7 Verify all connector tests pass in monorepo context

- [ ] 2. Core Enums & Validation
  - [ ] 2.1 Write tests for all enum classes with validation
  - [ ] 2.2 Create CloudProvider enum (ocean, linode, aws, vultr, hetzner, custom)
  - [ ] 2.3 Create ServerType enum (app, web, database, cache, worker, load-balancer)
  - [ ] 2.4 Create PhpVersion enum (php74, php80, php81, php82, php83, php84)
  - [ ] 2.5 Create DatabaseType enum (mysql, mysql8, postgres, mariadb)
  - [ ] 2.6 Create ProjectType enum (php, laravel, html, symfony, spa)
  - [ ] 2.7 Create CertificateType enum (letsencrypt, existing, clone)
  - [ ] 2.8 Create JobFrequency enum (minutely, hourly, nightly, weekly, monthly, reboot, custom)
  - [ ] 2.9 Create IntegrationType enum (github, gitlab, bitbucket, custom)
  - [ ] 2.10 Create remaining enums per API spec (MonitorType, FirewallRuleType, LogType, etc.)
  - [ ] 2.11 Add validate() method to each enum for pre-request validation
  - [ ] 2.12 Verify all enum tests pass

- [ ] 3. Exception Classes & Error Handling
  - [ ] 3.1 Write tests for exception classes
  - [ ] 3.2 Create ForgeException base exception class
  - [ ] 3.3 Create ValidationException for parameter validation errors
  - [ ] 3.4 Create ApiException for API response errors
  - [ ] 3.5 Create RateLimitException for 429 responses
  - [ ] 3.6 Create AuthenticationException for 401 responses
  - [ ] 3.7 Verify all exception tests pass

- [ ] 4. Servers Resource (Phase 1 - Core)
  - [ ] 4.1 Write tests for ServerResource and all Server requests
  - [ ] 4.2 Create ServerResource class with resource methods
  - [ ] 4.3 Create ListServersRequest with pagination support
  - [ ] 4.4 Create GetServerRequest with server ID parameter
  - [ ] 4.5 Create CreateServerRequest with full parameter validation
  - [ ] 4.6 Create UpdateServerRequest with validation
  - [ ] 4.7 Create DeleteServerRequest
  - [ ] 4.8 Create RebootServerRequest
  - [ ] 4.9 Create server service management requests (restart, stop, install)
  - [ ] 4.10 Create TestServersCommand for manual API testing
  - [ ] 4.11 Create JSON fixture responses for all server endpoints
  - [ ] 4.12 Verify all server tests pass

- [ ] 5. Sites Resource (Phase 1 - Core)
  - [ ] 5.1 Write tests for SiteResource and all Site requests
  - [ ] 5.2 Create SiteResource class with resource methods
  - [ ] 5.3 Create ListSitesRequest
  - [ ] 5.4 Create GetSiteRequest
  - [ ] 5.5 Create CreateSiteRequest with domain and project type validation
  - [ ] 5.6 Create UpdateSiteRequest
  - [ ] 5.7 Create DeleteSiteRequest
  - [ ] 5.8 Create Git repository management requests (install, update, remove)
  - [ ] 5.9 Create WordPress management requests (install, remove)
  - [ ] 5.10 Create environment file requests (get, update)
  - [ ] 5.11 Create PHP version change request
  - [ ] 5.12 Create TestSitesCommand for manual testing
  - [ ] 5.13 Create JSON fixture responses for all site endpoints
  - [ ] 5.14 Verify all site tests pass

- [ ] 6. SSL Certificates Resource (Phase 1 - Core)
  - [ ] 6.1 Write tests for SslCertificateResource and all certificate requests
  - [ ] 6.2 Create SslCertificateResource class (sub-resource of Site)
  - [ ] 6.3 Create ListCertificatesRequest
  - [ ] 6.4 Create GetCertificateRequest
  - [ ] 6.5 Create InstallLetsEncryptRequest with domains validation
  - [ ] 6.6 Create InstallCustomCertificateRequest with certificate and key validation
  - [ ] 6.7 Create ActivateCertificateRequest
  - [ ] 6.8 Create DeleteCertificateRequest
  - [ ] 6.9 Create TestSslCertificatesCommand for manual testing
  - [ ] 6.10 Create JSON fixture responses for all SSL endpoints
  - [ ] 6.11 Verify all SSL certificate tests pass

- [ ] 7. Deployments Resource (Phase 1 - Core)
  - [ ] 7.1 Write tests for DeploymentResource and all deployment requests
  - [ ] 7.2 Create DeploymentResource class
  - [ ] 7.3 Create GetDeploymentStatusRequest
  - [ ] 7.4 Create TriggerDeploymentRequest
  - [ ] 7.5 Create GetDeploymentLogRequest
  - [ ] 7.6 Create GetDeploymentScriptRequest
  - [ ] 7.7 Create UpdateDeploymentScriptRequest with script validation
  - [ ] 7.8 Create EnableQuickDeployRequest
  - [ ] 7.9 Create ResetDeploymentRequest
  - [ ] 7.10 Create ListDeploymentHistoryRequest
  - [ ] 7.11 Create TestDeploymentsCommand for manual testing
  - [ ] 7.12 Create JSON fixture responses for all deployment endpoints
  - [ ] 7.13 Verify all deployment tests pass

- [ ] 8. Databases Resource (Phase 1 - Core)
  - [ ] 8.1 Write tests for DatabaseResource and all database requests
  - [ ] 8.2 Create DatabaseResource class
  - [ ] 8.3 Create ListDatabasesRequest
  - [ ] 8.4 Create GetDatabaseRequest
  - [ ] 8.5 Create CreateDatabaseRequest with name validation
  - [ ] 8.6 Create UpdateDatabaseRequest
  - [ ] 8.7 Create DeleteDatabaseRequest
  - [ ] 8.8 Create database user management requests (list, create, update, delete)
  - [ ] 8.9 Create TestDatabasesCommand for manual testing
  - [ ] 8.10 Create JSON fixture responses for all database endpoints
  - [ ] 8.11 Verify all database tests pass

- [ ] 9. Firewall Rules Resource (Phase 2 - Server Management)
  - [ ] 9.1 Write tests for FirewallRuleResource and requests
  - [ ] 9.2 Create FirewallRuleResource class
  - [ ] 9.3 Create ListFirewallRulesRequest
  - [ ] 9.4 Create GetFirewallRuleRequest
  - [ ] 9.5 Create CreateFirewallRuleRequest with port and IP validation
  - [ ] 9.6 Create DeleteFirewallRuleRequest
  - [ ] 9.7 Create TestFirewallRulesCommand for manual testing
  - [ ] 9.8 Create JSON fixture responses for firewall endpoints
  - [ ] 9.9 Verify all firewall rule tests pass

- [ ] 10. SSH Keys Resource (Phase 2 - Server Management)
  - [ ] 10.1 Write tests for SshKeyResource and requests
  - [ ] 10.2 Create SshKeyResource class
  - [ ] 10.3 Create ListSshKeysRequest
  - [ ] 10.4 Create GetSshKeyRequest
  - [ ] 10.5 Create CreateSshKeyRequest with key validation
  - [ ] 10.6 Create DeleteSshKeyRequest
  - [ ] 10.7 Create TestSshKeysCommand for manual testing
  - [ ] 10.8 Create JSON fixture responses for SSH key endpoints
  - [ ] 10.9 Verify all SSH key tests pass

- [ ] 11. Scheduled Jobs Resource (Phase 2 - Server Management)
  - [ ] 11.1 Write tests for ScheduledJobResource and requests
  - [ ] 11.2 Create ScheduledJobResource class
  - [ ] 11.3 Create ListScheduledJobsRequest
  - [ ] 11.4 Create GetScheduledJobRequest
  - [ ] 11.5 Create CreateScheduledJobRequest with frequency and cron validation
  - [ ] 11.6 Create UpdateScheduledJobRequest
  - [ ] 11.7 Create DeleteScheduledJobRequest
  - [ ] 11.8 Create TestScheduledJobsCommand for manual testing
  - [ ] 11.9 Create JSON fixture responses for scheduled job endpoints
  - [ ] 11.10 Verify all scheduled job tests pass

- [ ] 12. Background Processes Resource (Phase 2 - Server Management)
  - [ ] 12.1 Write tests for BackgroundProcessResource and requests
  - [ ] 12.2 Create BackgroundProcessResource class
  - [ ] 12.3 Create ListBackgroundProcessesRequest
  - [ ] 12.4 Create GetBackgroundProcessRequest
  - [ ] 12.5 Create CreateBackgroundProcessRequest with command validation
  - [ ] 12.6 Create UpdateBackgroundProcessRequest
  - [ ] 12.7 Create DeleteBackgroundProcessRequest
  - [ ] 12.8 Create RestartBackgroundProcessRequest
  - [ ] 12.9 Create TestBackgroundProcessesCommand for manual testing
  - [ ] 12.10 Create JSON fixture responses for background process endpoints
  - [ ] 12.11 Verify all background process tests pass

- [ ] 13. Organizations Resource (Phase 3 - Organization & Access)
  - [ ] 13.1 Write tests for OrganizationResource and requests
  - [ ] 13.2 Create OrganizationResource class
  - [ ] 13.3 Create ListOrganizationsRequest
  - [ ] 13.4 Create GetOrganizationRequest
  - [ ] 13.5 Create CreateOrganizationRequest with name validation
  - [ ] 13.6 Create UpdateOrganizationRequest
  - [ ] 13.7 Create DeleteOrganizationRequest
  - [ ] 13.8 Create organization member management requests (list, add, remove)
  - [ ] 13.9 Create TestOrganizationsCommand for manual testing
  - [ ] 13.10 Create JSON fixture responses for organization endpoints
  - [ ] 13.11 Verify all organization tests pass

- [ ] 14. Teams Resource (Phase 3 - Organization & Access)
  - [ ] 14.1 Write tests for TeamResource and requests
  - [ ] 14.2 Create TeamResource class
  - [ ] 14.3 Create ListTeamsRequest
  - [ ] 14.4 Create GetTeamRequest
  - [ ] 14.5 Create CreateTeamRequest with validation
  - [ ] 14.6 Create UpdateTeamRequest
  - [ ] 14.7 Create DeleteTeamRequest
  - [ ] 14.8 Create team member management requests (list, add, remove)
  - [ ] 14.9 Create TestTeamsCommand for manual testing
  - [ ] 14.10 Create JSON fixture responses for team endpoints
  - [ ] 14.11 Verify all team tests pass

- [ ] 15. Roles Resource (Phase 3 - Organization & Access)
  - [ ] 15.1 Write tests for RoleResource and requests
  - [ ] 15.2 Create RoleResource class
  - [ ] 15.3 Create ListRolesRequest
  - [ ] 15.4 Create GetRoleRequest
  - [ ] 15.5 Create CreateRoleRequest with permissions validation
  - [ ] 15.6 Create UpdateRoleRequest
  - [ ] 15.7 Create DeleteRoleRequest
  - [ ] 15.8 Create TestRolesCommand for manual testing
  - [ ] 15.9 Create JSON fixture responses for role endpoints
  - [ ] 15.10 Verify all role tests pass

- [ ] 16. User Resource (Phase 3 - Organization & Access)
  - [ ] 16.1 Write tests for UserResource and requests
  - [ ] 16.2 Create UserResource class
  - [ ] 16.3 Create GetCurrentUserRequest
  - [ ] 16.4 Create UpdateUserProfileRequest
  - [ ] 16.5 Create API token management requests (list, create, delete)
  - [ ] 16.6 Create TestUserCommand for manual testing
  - [ ] 16.7 Create JSON fixture responses for user endpoints
  - [ ] 16.8 Verify all user tests pass

- [ ] 17. Commands Resource (Phase 4 - Advanced Features)
  - [ ] 17.1 Write tests for CommandResource and requests
  - [ ] 17.2 Create CommandResource class
  - [ ] 17.3 Create ListCommandsRequest
  - [ ] 17.4 Create GetCommandRequest
  - [ ] 17.5 Create RunCommandRequest with command validation
  - [ ] 17.6 Create GetCommandOutputRequest
  - [ ] 17.7 Create TestCommandsCommand for manual testing
  - [ ] 17.8 Create JSON fixture responses for command endpoints
  - [ ] 17.9 Verify all command tests pass

- [ ] 18. Nginx Resource (Phase 4 - Advanced Features)
  - [ ] 18.1 Write tests for NginxResource and requests
  - [ ] 18.2 Create NginxResource class
  - [ ] 18.3 Create GetNginxTemplateRequest
  - [ ] 18.4 Create UpdateNginxTemplateRequest with template validation
  - [ ] 18.5 Create ResetNginxTemplateRequest
  - [ ] 18.6 Create TestNginxCommand for manual testing
  - [ ] 18.7 Create JSON fixture responses for nginx endpoints
  - [ ] 18.8 Verify all nginx tests pass

- [ ] 19. Redirect Rules Resource (Phase 4 - Advanced Features)
  - [ ] 19.1 Write tests for RedirectRuleResource and requests
  - [ ] 19.2 Create RedirectRuleResource class
  - [ ] 19.3 Create ListRedirectRulesRequest
  - [ ] 19.4 Create GetRedirectRuleRequest
  - [ ] 19.5 Create CreateRedirectRuleRequest with pattern and target validation
  - [ ] 19.6 Create UpdateRedirectRuleRequest
  - [ ] 19.7 Create DeleteRedirectRuleRequest
  - [ ] 19.8 Create TestRedirectRulesCommand for manual testing
  - [ ] 19.9 Create JSON fixture responses for redirect rule endpoints
  - [ ] 19.10 Verify all redirect rule tests pass

- [ ] 20. Logs Resource (Phase 4 - Advanced Features)
  - [ ] 20.1 Write tests for LogResource and requests
  - [ ] 20.2 Create LogResource class
  - [ ] 20.3 Create GetServerLogsRequest with log type enum
  - [ ] 20.4 Create GetApplicationLogsRequest
  - [ ] 20.5 Create TestLogsCommand for manual testing
  - [ ] 20.6 Create JSON fixture responses for log endpoints
  - [ ] 20.7 Verify all log tests pass

- [ ] 21. Monitors Resource (Phase 4 - Advanced Features)
  - [ ] 21.1 Write tests for MonitorResource and requests
  - [ ] 21.2 Create MonitorResource class
  - [ ] 21.3 Create ListMonitorsRequest
  - [ ] 21.4 Create GetMonitorRequest
  - [ ] 21.5 Create CreateMonitorRequest with monitor type validation
  - [ ] 21.6 Create UpdateMonitorRequest
  - [ ] 21.7 Create DeleteMonitorRequest
  - [ ] 21.8 Create TestMonitorsCommand for manual testing
  - [ ] 21.9 Create JSON fixture responses for monitor endpoints
  - [ ] 21.10 Verify all monitor tests pass

- [ ] 22. Providers Resource (Phase 5 - Integrations & Utilities)
  - [ ] 22.1 Write tests for ProviderResource and requests
  - [ ] 22.2 Create ProviderResource class
  - [ ] 22.3 Create ListProvidersRequest
  - [ ] 22.4 Create GetProviderRequest
  - [ ] 22.5 Create CreateProviderRequest with provider type validation
  - [ ] 22.6 Create DeleteProviderRequest
  - [ ] 22.7 Create TestProvidersCommand for manual testing
  - [ ] 22.8 Create JSON fixture responses for provider endpoints
  - [ ] 22.9 Verify all provider tests pass

- [ ] 23. Integrations Resource (Phase 5 - Integrations & Utilities)
  - [ ] 23.1 Write tests for IntegrationResource and requests
  - [ ] 23.2 Create IntegrationResource class
  - [ ] 23.3 Create ListIntegrationsRequest
  - [ ] 23.4 Create GetIntegrationRequest
  - [ ] 23.5 Create CreateIntegrationRequest with integration type validation
  - [ ] 23.6 Create DeleteIntegrationRequest
  - [ ] 23.7 Create TestIntegrationsCommand for manual testing
  - [ ] 23.8 Create JSON fixture responses for integration endpoints
  - [ ] 23.9 Verify all integration tests pass

- [ ] 24. Recipes Resource (Phase 5 - Integrations & Utilities)
  - [ ] 24.1 Write tests for RecipeResource and requests
  - [ ] 24.2 Create RecipeResource class
  - [ ] 24.3 Create ListRecipesRequest
  - [ ] 24.4 Create GetRecipeRequest
  - [ ] 24.5 Create CreateRecipeRequest with script validation
  - [ ] 24.6 Create UpdateRecipeRequest
  - [ ] 24.7 Create DeleteRecipeRequest
  - [ ] 24.8 Create RunRecipeRequest
  - [ ] 24.9 Create TestRecipesCommand for manual testing
  - [ ] 24.10 Create JSON fixture responses for recipe endpoints
  - [ ] 24.11 Verify all recipe tests pass

- [ ] 25. Server Credentials Resource (Phase 5 - Integrations & Utilities)
  - [ ] 25.1 Write tests for ServerCredentialResource and requests
  - [ ] 25.2 Create ServerCredentialResource class
  - [ ] 25.3 Create ListServerCredentialsRequest
  - [ ] 25.4 Create GetServerCredentialRequest
  - [ ] 25.5 Create CreateServerCredentialRequest with validation
  - [ ] 25.6 Create UpdateServerCredentialRequest
  - [ ] 25.7 Create DeleteServerCredentialRequest
  - [ ] 25.8 Create TestServerCredentialsCommand for manual testing
  - [ ] 25.9 Create JSON fixture responses for server credential endpoints
  - [ ] 25.10 Verify all server credential tests pass

- [ ] 26. Security Rules Resource (Phase 5 - Integrations & Utilities)
  - [ ] 26.1 Write tests for SecurityRuleResource and requests
  - [ ] 26.2 Create SecurityRuleResource class
  - [ ] 26.3 Create ListSecurityRulesRequest
  - [ ] 26.4 Create GetSecurityRuleRequest
  - [ ] 26.5 Create CreateSecurityRuleRequest with validation
  - [ ] 26.6 Create UpdateSecurityRuleRequest
  - [ ] 26.7 Create DeleteSecurityRuleRequest
  - [ ] 26.8 Create TestSecurityRulesCommand for manual testing
  - [ ] 26.9 Create JSON fixture responses for security rule endpoints
  - [ ] 26.10 Verify all security rule tests pass

- [ ] 27. Comprehensive README Documentation
  - [ ] 27.1 Write installation section with composer require instructions
  - [ ] 27.2 Write configuration section with .env and config file examples
  - [ ] 27.3 Write quick start guide with basic usage example
  - [ ] 27.4 Write usage examples for all 22 resources with code samples
  - [ ] 27.5 Document all artisan testing commands with examples
  - [ ] 27.6 Write error handling section with exception examples
  - [ ] 27.7 Write troubleshooting section for common issues
  - [ ] 27.8 Add table of contents with anchor links
  - [ ] 27.9 Add badges for tests, version, license
  - [ ] 27.10 Write contributing guidelines
  - [ ] 27.11 Review README for completeness and accuracy

- [ ] 28. Package Polish & Documentation
  - [ ] 28.1 Create CHANGELOG.md with initial version entry
  - [ ] 28.2 Update composer.json with proper keywords, description, authors
  - [ ] 28.3 Add LICENSE file (MIT or as specified)
  - [ ] 28.4 Create .gitattributes for export-ignore
  - [ ] 28.5 Ensure all PHPDoc blocks are complete on public methods
  - [ ] 28.6 Run `composer ready` and fix any issues
  - [ ] 28.7 Verify package works in Laravel Zero context
  - [ ] 28.8 Verify all tests pass one final time
