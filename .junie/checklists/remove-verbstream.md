# Verbstream Removal Checklist

This checklist outlines the steps required to completely remove the verbstream package from the monorepo. The package is not used in production and has never been released.

## Preparation

- [ ] Verify that verbstream is not used in any production code
- [ ] Create a new branch for the removal process
- [ ] Run tests to ensure the current state is working

## Remove Package Files

- [ ] Remove the entire verbstream package directory (`packages/verbstream`)

## Remove Dependencies

- [ ] Remove verbstream from composer.json dependencies

## Update Models

- [ ] Update app/Models/TeamInvitation.php to remove verbstream dependency
- [ ] Update app/Models/User.php to remove verbstream traits (HasProfilePhoto, HasTeams)
- [ ] Update app/Models/Membership.php to remove verbstream inheritance
- [ ] Update app/Models/Team.php to remove verbstream inheritance

## Update Routes and Configuration

- [ ] Update routes/web.php to remove verbstream auth_session reference
- [ ] Remove any verbstream configuration files or references

## Update Factories

- [ ] Update database/factories/UserFactory.php to remove verbstream Features reference

## Verification

- [ ] Run composer update to update dependencies
- [ ] Ensure no references to verbstream remain in the codebase
- [ ] Run tests to confirm nothing breaks
- [ ] Run `composer ready` to ensure all tests and checks pass

## Final Steps

- [ ] Commit changes with a descriptive message
- [ ] Create a pull request for review
- [ ] After approval, merge the changes
