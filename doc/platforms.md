# Platform adapters

Spatial types identify the Doctrine DBAL platform by its class. The built-in
adapters support the MySQL family (including MariaDB) and PostgreSQL.

To support a custom Doctrine DBAL platform, create an adapter implementing
`CrEOF\Spatial\DBAL\Platform\PlatformInterface` and register it during your
application bootstrap, before Doctrine creates or uses spatial types:

```php
use CrEOF\Spatial\DBAL\Platform\SpatialPlatformRegistry;

SpatialPlatformRegistry::register(
    'App\\DBAL\\Platforms\\CustomPlatform',
    'App\\Spatial\\DBAL\\Platform\\CustomPlatform'
);
```

The registration is framework-independent. In a plain PHP application it can
live in the bootstrap file; framework integrations should make the same call
during their application boot process.

Mappings apply to subclasses. A mapping registered for a concrete platform
class takes precedence over the mapping for a parent class.
