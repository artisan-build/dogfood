#!/bin/bash

# Script to set up CI/CD for all packages
# This script:
# 1. Renames .forked files to their proper names
# 2. Updates namespaces in test files
# 3. Adds GitHub Actions workflows
# 4. Adds phpunit.xml.dist files

set -e

PACKAGES_DIR="packages"
TEMPLATE_DIR="."

# Packages that require Flux UI authentication
FLUX_PACKAGES=("code-chat-client" "hallway-flux" "verbs-flux")

# Function to check if package requires Flux
requires_flux() {
    local package=$1
    for flux_pkg in "${FLUX_PACKAGES[@]}"; do
        if [[ "$package" == "$flux_pkg" ]]; then
            return 0
        fi
    done
    return 1
}

# Function to get package namespace from composer.json
get_package_namespace() {
    local package_dir=$1
    local composer_file="$package_dir/composer.json"
    
    if [ -f "$composer_file" ]; then
        # Extract the first PSR-4 namespace from autoload
        namespace=$(grep -A10 '"autoload"' "$composer_file" | grep -A5 '"psr-4"' | grep '":' | grep -v 'psr-4' | head -1 | sed 's/.*"\(.*\)\\\\".*/\1/' | tr -d '[:space:]')
        echo "$namespace"
    else
        echo ""
    fi
}

# Function to get service provider class from composer.json
get_service_provider() {
    local package_dir=$1
    local composer_file="$package_dir/composer.json"
    
    if [ -f "$composer_file" ]; then
        # Extract provider from Laravel extra section
        provider=$(grep -A10 '"extra"' "$composer_file" | grep -A5 '"laravel"' | grep -A3 '"providers"' | grep '\\' | head -1 | cut -d'"' -f2)
        echo "$provider"
    else
        echo ""
    fi
}

# Loop through all packages
for package_dir in "$PACKAGES_DIR"/*; do
    if [ -d "$package_dir" ]; then
        package_name=$(basename "$package_dir")
        echo "Setting up CI for package: $package_name"
        
        # Get package namespace
        namespace=$(get_package_namespace "$package_dir")
        if [ -z "$namespace" ]; then
            echo "  ⚠️  Could not determine namespace for $package_name, skipping..."
            continue
        fi
        
        echo "  - Namespace: $namespace"
        
        # Step 1: Handle test files
        if [ -d "$package_dir/tests" ]; then
            # Rename .forked files
            if [ -f "$package_dir/tests/Pest.php.forked" ] && [ ! -f "$package_dir/tests/Pest.php" ]; then
                echo "  - Renaming Pest.php.forked to Pest.php"
                mv "$package_dir/tests/Pest.php.forked" "$package_dir/tests/Pest.php"
                
                # Update namespace in Pest.php
                sed -i.bak "s/ArtisanBuild\\\\Skeleton\\\\Tests/${namespace}\\\\Tests/g" "$package_dir/tests/Pest.php"
                rm "$package_dir/tests/Pest.php.bak"
            fi
            
            if [ -f "$package_dir/tests/TestCase.php.forked" ] && [ ! -f "$package_dir/tests/TestCase.php" ]; then
                echo "  - Renaming TestCase.php.forked to TestCase.php"
                mv "$package_dir/tests/TestCase.php.forked" "$package_dir/tests/TestCase.php"
                
                # Update namespace and provider in TestCase.php
                sed -i.bak "s/namespace ArtisanBuild\\\\Skeleton\\\\Tests;/namespace ${namespace}\\\\Tests;/g" "$package_dir/tests/TestCase.php"
                sed -i.bak "s/VendorName\\\\\\\\Skeleton\\\\\\\\Database\\\\\\\\Factories/${namespace}\\\\\\\\Database\\\\\\\\Factories/g" "$package_dir/tests/TestCase.php"
                
                # Update service provider
                provider=$(get_service_provider "$package_dir")
                if [ -n "$provider" ]; then
                    sed -i.bak "s/use VendorName\\\\Skeleton\\\\SkeletonServiceProvider;/use ${provider};/g" "$package_dir/tests/TestCase.php"
                    sed -i.bak "s/SkeletonServiceProvider::class/$(basename "$provider" | sed 's/\\//')::class/g" "$package_dir/tests/TestCase.php"
                fi
                
                rm "$package_dir/tests/TestCase.php.bak"
            fi
        fi
        
        # Step 2: Create .github/workflows directory
        mkdir -p "$package_dir/.github/workflows"
        
        # Step 3: Copy appropriate workflow template
        if requires_flux "$package_name"; then
            echo "  - Using Flux-enabled workflow"
            cp "$TEMPLATE_DIR/.github/workflows/package-tests-with-flux-template.yml" "$package_dir/.github/workflows/tests.yml"
        else
            echo "  - Using standard workflow"
            cp "$TEMPLATE_DIR/.github/workflows/package-tests-template.yml" "$package_dir/.github/workflows/tests.yml"
        fi
        
        # Step 4: Add phpunit.xml.dist if it doesn't exist
        if [ ! -f "$package_dir/phpunit.xml.dist" ]; then
            echo "  - Adding phpunit.xml.dist"
            cp "$TEMPLATE_DIR/phpunit.xml.dist.template" "$package_dir/phpunit.xml.dist"
        fi
        
        # Step 5: Ensure .gitattributes excludes these files from archive
        if [ -f "$package_dir/.gitattributes" ]; then
            # Check if .github and phpunit.xml.dist are already in .gitattributes
            if ! grep -q "^/.github" "$package_dir/.gitattributes"; then
                echo "  - Updating .gitattributes"
                echo "" >> "$package_dir/.gitattributes"
                echo "# CI files excluded from archive in monorepo context" >> "$package_dir/.gitattributes"
                echo "/.github export-ignore" >> "$package_dir/.gitattributes"
                echo "/phpunit.xml.dist export-ignore" >> "$package_dir/.gitattributes"
            fi
        fi
        
        echo "  ✓ Completed setup for $package_name"
        echo ""
    fi
done

echo "CI setup complete for all packages!"
echo ""
echo "Summary of changes:"
echo "- Renamed .forked test files to their proper names"
echo "- Updated namespaces in test files to match package namespaces"
echo "- Added GitHub Actions workflows to each package"
echo "- Added phpunit.xml.dist files for isolated testing"
echo ""
echo "Next steps:"
echo "1. Review the changes with 'git status'"
echo "2. Test a package locally with 'cd packages/[name] && composer test'"
echo "3. Commit the changes"
echo "4. Run 'php artisan kibble:split' to push changes to individual repositories"
echo "5. Set up these GitHub secrets for packages that need Flux:"
echo "   - FLUX_USERNAME"
echo "   - FLUX_LICENSE_KEY"