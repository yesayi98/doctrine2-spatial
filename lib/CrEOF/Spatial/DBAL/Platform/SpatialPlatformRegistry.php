<?php
/**
 * Copyright (C) 2015 Derek J. Lambert
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace CrEOF\Spatial\DBAL\Platform;

use CrEOF\Spatial\Exception\UnsupportedPlatformException;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * Resolves Doctrine DBAL platform classes to spatial platform adapters.
 *
 * Applications may register an adapter for a custom DBAL platform during
 * bootstrap, before the spatial Doctrine types are used.
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
final class SpatialPlatformRegistry
{
    /**
     * @var array
     */
    private static $mappings = array(
        'Doctrine\\DBAL\\Platforms\\AbstractMySQLPlatform' => 'CrEOF\\Spatial\\DBAL\\Platform\\MySql',
        'Doctrine\\DBAL\\Platforms\\MySqlPlatform'          => 'CrEOF\\Spatial\\DBAL\\Platform\\MySql',
        'Doctrine\\DBAL\\Platforms\\MySQLPlatform'          => 'CrEOF\\Spatial\\DBAL\\Platform\\MySql',
        'Doctrine\\DBAL\\Platforms\\PostgreSqlPlatform'     => 'CrEOF\\Spatial\\DBAL\\Platform\\PostgreSql',
        'Doctrine\\DBAL\\Platforms\\PostgreSQLPlatform'     => 'CrEOF\\Spatial\\DBAL\\Platform\\PostgreSql',
    );

    /**
     * Register a spatial adapter for a Doctrine DBAL platform class.
     *
     * A mapping for a concrete platform class takes precedence over a mapping
     * for one of its parent classes.
     *
     * @param string $doctrinePlatformClass
     * @param string $spatialPlatformClass
     *
     * @return void
     */
    public static function register($doctrinePlatformClass, $spatialPlatformClass)
    {
        self::$mappings[$doctrinePlatformClass] = $spatialPlatformClass;
    }

    /**
     * @param AbstractPlatform $platform
     *
     * @return PlatformInterface
     * @throws UnsupportedPlatformException
     */
    public static function resolve(AbstractPlatform $platform)
    {
        for ($class = get_class($platform); $class; $class = get_parent_class($class)) {
            if (isset(self::$mappings[$class])) {
                $spatialPlatformClass = self::$mappings[$class];

                return new $spatialPlatformClass;
            }
        }

        throw new UnsupportedPlatformException(sprintf(
            'DBAL platform "%s" is not currently supported.',
            get_class($platform)
        ));
    }
}
