<?php

use Ovpn\Core\Config;
/**
 * Class Database
 * todo fix - decorate it with two classes
 */
abstract class Database extends Kohana_Database
{
    /**
     * @inheritdoc
     */
    public static function instance($name = null, array $config = null)
    {
        if ($name === null) {
            // Use the default instance name
            $name = Database::$default;
        }

        if ( ! isset(Database::$instances[$name])) {
            if ($config === null) {

                $config = (new Config())
                    ->get("database:$name");
            }

            if ( ! isset($config['type'])) {
                throw new Kohana_Exception('Database type not defined in :name configuration',
                    array(':name' => $name));
            }

            // Set the driver class name
            $driver = 'Database_'.ucfirst($config['type']);

            // Create the database connection instance
            $driver = new $driver($name, $config);

            // Store the database instance
            Database::$instances[$name] = $driver;
        }

        return Database::$instances[$name];
    }
}