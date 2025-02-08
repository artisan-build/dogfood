<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Filename & Format
    |--------------------------------------------------------------------------
    |
    | The default filename
    |
    */

    'filename' => '_ide_helper.php',

    /*
    |--------------------------------------------------------------------------
    | Models filename
    |--------------------------------------------------------------------------
    |
    | The default filename for model helpers
    |
    */

    'models_filename' => '_ide_helper_models.php',

    /*
    |--------------------------------------------------------------------------
    | Where to write the PhpStorm specific meta file
    |--------------------------------------------------------------------------
    |
    | PhpStorm also supports the directory `.phpstorm.meta.php/`
    |
    */

    'meta_filename' => '.phpstorm.meta.php',

    /*
    |--------------------------------------------------------------------------
    | Models to include
    |--------------------------------------------------------------------------
    |
    | Which models to include, you can also specify different directories
    | Supported PSR-4 autoloading, so just specify the full namespace
    |
    */

    'model_locations' => [
        'app/Models',
        'packages/*/src/Models',
    ],

    /*
    |--------------------------------------------------------------------------
    | Models to ignore
    |--------------------------------------------------------------------------
    |
    | Define which models should be ignored.
    |
    */

    'ignored_models' => [

    ],

    /*
    |--------------------------------------------------------------------------
    | Extra classes
    |--------------------------------------------------------------------------
    |
    | These implementations are not really extended, but called with magic functions
    |
    */

    'extra' => [
        'Eloquent' => [\Illuminate\Database\Eloquent\Builder::class, \Illuminate\Database\Query\Builder::class],
        'Session' => [\Illuminate\Session\Store::class],
    ],

    'magic' => [],

    /*
    |--------------------------------------------------------------------------
    | Interface implementations
    |--------------------------------------------------------------------------
    |
    | These interfaces will be replaced with the implementing class. Some interfaces
    | are detected by the helpers, others can be listed below.
    |
    */

    'interfaces' => [

    ],

    /*
    |--------------------------------------------------------------------------
    | Support for custom DB types
    |--------------------------------------------------------------------------
    |
    | This setting allow you to map any custom database type (that you may have
    | created using CREATE TYPE statement or imported using database plugin
    | / extension to a Doctrine type.
    |
    | Each key in this array is a name of the Doctrine2 DBAL Platform. Currently valid names are:
    | 'postgresql', 'db2', 'drizzle', 'mysql', 'oracle', 'sqlanywhere', 'sqlite', 'mssql'
    |
    | This name is returned by getName() method of the specific Doctrine/DBAL/Platforms/AbstractPlatform descendant
    |
    | The value of the array is an array of type mappings. Key is the name of the custom type,
    | (for example, "jsonb" from Postgres 9.4) and the value is the name of the corresponding Doctrine2 type (in
    | our case it is 'json_array'. Doctrine types are listed here:
    | https://www.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/types.html#types
    |
    | So to support jsonb in your models when working with Postgres, just add the following entry to the array below:
    |
    | "postgresql" => array(
    |       "jsonb" => "json_array",
    |  ),
    |
    */
    'custom_db_types' => [

    ],

    /*
    |--------------------------------------------------------------------------
    | Support for camel cased models
    |--------------------------------------------------------------------------
    |
    | There are some Laravel packages (such as Eloquent-Sluggable) that allow for
    | accessing camel cased models, e.g., 'EloquentSluggable' instead of
    | 'eloquent-sluggable'. This is very helpful for IDE auto-completion.
    | By default, the package will detect this setting from Laravel's configuration.
    | You can also enable/disable it manually here.
    |
    */
    'camel_case_models' => true,

    /*
    |--------------------------------------------------------------------------
    | Write Model Magic methods
    |--------------------------------------------------------------------------
    |
    | Set to false to disable write magic methods of model
    |
    */

    'write_model_magic_where' => true,

    /*
    |--------------------------------------------------------------------------
    | Write Model External classes
    |--------------------------------------------------------------------------
    |
    | Set to false to disable writing model relation count properties
    |
    */

    'write_model_external_builder_methods' => true,

    /*
    |--------------------------------------------------------------------------
    | Write Model relation count properties
    |--------------------------------------------------------------------------
    |
    | Set to false to disable writing of relation count properties to model DocBlocks.
    |
    */

    'write_model_relation_count_properties' => true,

    /*
    |--------------------------------------------------------------------------
    | Write Eloquent Model Mixins
    |--------------------------------------------------------------------------
    |
    | This will add the necessary DocBlock mixins to the model class
    | contained in the Laravel Framework. This helps the IDE with
    | auto-completion.
    |
    | Please be aware that this setting changes a file within the /vendor directory.
    |
    */

    'write_eloquent_model_mixins' => false,
];
