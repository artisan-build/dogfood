# Verbstream Removal Checklist

This checklist outlines the steps required to completely remove the verbstream package from the monorepo. The package is not used in production and has never been released.

## Preparation

- [x] Verify that verbstream is not used in any production code
- [x] Create a new branch for the removal process
- [x] Run tests to ensure the current state is working

## Remove Package Files

- [x] Remove the entire verbstream package directory (`packages/verbstream`)

## Remove Dependencies

- [x] Remove verbstream from composer.json dependencies

## Update Models

- [x] Update app/Models/TeamInvitation.php to remove verbstream dependency
- [x] Update app/Models/User.php to remove verbstream traits (HasProfilePhoto, HasTeams)
- [x] Update app/Models/Membership.php to remove verbstream inheritance
- [x] Update app/Models/Team.php to remove verbstream inheritance

## Update Routes and Configuration

- [x] Update routes/web.php to remove verbstream auth_session reference
- [x] Remove any verbstream configuration files or references

## Update Factories

- [x] Update database/factories/UserFactory.php to remove verbstream Features reference

## Verification

- [x] Run composer update to update dependencies
- [x] Ensure no references to verbstream remain in the codebase
- [x] Run tests to confirm nothing breaks
- [x] Run `composer ready` to ensure all tests and checks pass

## Final Steps

- [x] Commit changes with a descriptive message
- [x] Create a pull request for review
- [x] After approval, merge the changes
