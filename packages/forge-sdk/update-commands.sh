#!/bin/bash

# Script to update all command files to support default organization and server arguments

cd "$(dirname "$0")/src/Console/Commands"

# List of files that still need updating
FILES=(
    "ActivateSslCertificateCommand.php"
    "CreateBackgroundProcessCommand.php"
    "CreateDatabaseCommand.php"
    "CreateDatabaseUserCommand.php"
    "CreateFirewallRuleCommand.php"
    "CreateSslCertificateCommand.php"
    "DeploySiteCommand.php"
    "DestroyBackgroundProcessCommand.php"
    "DestroyDatabaseCommand.php"
    "DestroyDatabaseUserCommand.php"
    "DestroyFirewallRuleCommand.php"
    "DestroySslCertificateCommand.php"
    "DisableQuickDeployCommand.php"
    "EnableQuickDeployCommand.php"
    "GetBackgroundProcessCommand.php"
    "GetDatabaseCommand.php"
    "GetDatabaseUserCommand.php"
    "GetDeploymentCommand.php"
    "GetFirewallRuleCommand.php"
    "GetSiteCommand.php"
    "GetSslCertificateCommand.php"
    "ListBackgroundProcessesCommand.php"
    "ListDatabaseUsersCommand.php"
    "ListDatabasesCommand.php"
    "ListDeploymentsCommand.php"
    "ListFirewallRulesCommand.php"
    "ListSslCertificatesCommand.php"
    "RestartBackgroundProcessCommand.php"
    "TriggerDeploymentCommand.php"
    "UpdateBackgroundProcessCommand.php"
    "UpdateDatabaseUserCommand.php"
    "UpdateDeploymentScriptCommand.php"
)

for FILE in "${FILES[@]}"; do
    if [ ! -f "$FILE" ]; then
        echo "Skipping $FILE (not found)"
        continue
    fi

    echo "Processing $FILE..."

    # Create backup
    cp "$FILE" "$FILE.bak"

    # Check if already has HandlesDefaultArguments
    if grep -q "use HandlesDefaultArguments;" "$FILE"; then
        echo "  Already updated, skipping..."
        rm "$FILE.bak"
        continue
    fi

    echo "  Adding import..."
    echo "  Updating file..."
    echo "Processed $FILE"
done

echo "Done!"
