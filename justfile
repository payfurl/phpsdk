# payfurl/phpsdk. `just` lists the recipes.

# Unit tests and the static checks, as CI runs them
test: vendor
    vendor/bin/phpunit
    vendor/bin/phpstan analyse --memory-limit=1G --no-progress
    vendor/bin/php-cs-fixer fix --dry-run --diff
    composer validate --strict
    composer audit

# Apply the code style (PER-CS)
fix: vendor
    vendor/bin/php-cs-fixer fix

# The integration tests, in payfurl/platform, against this checkout and an API built from source (Docker); args go to PHPUnit
e2e *args:
    #!/usr/bin/env bash
    set -euo pipefail
    sdk=$(pwd)
    platform=${PLATFORM_DIR:-}
    if [ -z "$platform" ]; then
        for candidate in ../platform ../../platform; do
            if [ -f "$candidate/e2e/api-tests/phpsdk/docker-compose.yml" ]; then platform=$candidate; break; fi
        done
    fi
    [ -n "$platform" ] && [ -f "$platform/e2e/api-tests/phpsdk/docker-compose.yml" ] \
        || { echo "no payfurl/platform checkout at ../platform or ../../platform: set PLATFORM_DIR" >&2; exit 1; }
    cd "$platform" && PHPSDK_DIR="$sdk" just e2e phpsdk {{args}}

[private]
vendor:
    @test -d vendor || composer install --no-progress
