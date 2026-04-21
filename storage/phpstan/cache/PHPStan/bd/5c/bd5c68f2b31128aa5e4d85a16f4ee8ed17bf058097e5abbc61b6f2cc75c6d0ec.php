<?php declare(strict_types = 1);

// osfsl-/var/www/html/vendor/composer/../laravel/framework/src/Illuminate/Support/Facades/Schema.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Support\Facades\Schema
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-c37b1445d80d5f3ebabd335997e691099ddc351aaea6615459dad635094b1992-8.4.20-6.70.0.0',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Support\\Facades\\Schema',
        'filename' => '/var/www/html/vendor/composer/../laravel/framework/src/Illuminate/Support/Facades/Schema.php',
      ),
    ),
    'namespace' => 'Illuminate\\Support\\Facades',
    'name' => 'Illuminate\\Support\\Facades\\Schema',
    'shortName' => 'Schema',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @method static void defaultStringLength(int $length)
 * @method static void defaultTimePrecision(int|null $precision)
 * @method static void defaultMorphKeyType(string $type)
 * @method static void morphUsingUuids()
 * @method static void morphUsingUlids()
 * @method static bool createDatabase(string $name)
 * @method static bool dropDatabaseIfExists(string $name)
 * @method static array<array> getSchemas()
 * @method static bool hasTable(string $table)
 * @method static bool hasView(string $view)
 * @method static array<array> getTables(string|string[]|null $schema = null)
 * @method static array<string> getTableListing(string|string[]|null $schema = null, bool $schemaQualified = true)
 * @method static array<array> getViews(string|string[]|null $schema = null)
 * @method static array<array> getTypes(string|string[]|null $schema = null)
 * @method static bool hasColumn(string $table, string $column)
 * @method static bool hasColumns(string $table, array<string> $columns)
 * @method static void whenTableHasColumn(string $table, string $column, \\Closure $callback)
 * @method static void whenTableDoesntHaveColumn(string $table, string $column, \\Closure $callback)
 * @method static void whenTableHasIndex(string $table, string|array $index, \\Closure $callback, string|null $type = null)
 * @method static void whenTableDoesntHaveIndex(string $table, string|array $index, \\Closure $callback, string|null $type = null)
 * @method static string getColumnType(string $table, string $column, bool $fullDefinition = false)
 * @method static array<string> getColumnListing(string $table)
 * @method static array<array> getColumns(string $table)
 * @method static array<array> getIndexes(string $table)
 * @method static array<string> getIndexListing(string $table)
 * @method static bool hasIndex(string $table, string|array $index, string|null $type = null)
 * @method static array getForeignKeys(string $table)
 * @method static void table(string $table, \\Closure $callback)
 * @method static void create(string $table, \\Closure $callback)
 * @method static void drop(string $table)
 * @method static void dropIfExists(string $table)
 * @method static void dropColumns(string $table, string|array<string> $columns)
 * @method static void dropAllTables()
 * @method static void dropAllViews()
 * @method static void dropAllTypes()
 * @method static void rename(string $from, string $to)
 * @method static bool enableForeignKeyConstraints()
 * @method static bool disableForeignKeyConstraints()
 * @method static mixed withoutForeignKeyConstraints(\\Closure $callback)
 * @method static void ensureVectorExtensionExists(string|null $schema = null)
 * @method static void ensureExtensionExists(string $name, string|null $schema = null)
 * @method static string[]|null getCurrentSchemaListing()
 * @method static string|null getCurrentSchemaName()
 * @method static array parseSchemaAndTable(string $reference, string|bool|null $withDefaultSchema = null)
 * @method static \\Illuminate\\Database\\Connection getConnection()
 * @method static void blueprintResolver(\\Closure $resolver)
 * @method static void macro(string $name, object|callable $macro)
 * @method static void mixin(object $mixin, bool $replace = true)
 * @method static bool hasMacro(string $name)
 * @method static void flushMacros()
 *
 * @see \\Illuminate\\Database\\Schema\\Builder
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 59,
    'endLine' => 88,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Support\\Facades\\Facade',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'cached' => 
      array (
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Schema',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Schema',
        'name' => 'cached',
        'modifiers' => 18,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 66,
            'endLine' => 66,
            'startTokenPos' => 29,
            'startFilePos' => 3567,
            'endTokenPos' => 29,
            'endFilePos' => 3571,
          ),
        ),
        'docComment' => '/**
 * Indicates if the resolved facade should be cached.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 66,
        'endLine' => 66,
        'startColumn' => 5,
        'endColumn' => 37,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
    ),
    'immediateMethods' => 
    array (
      'connection' => 
      array (
        'name' => 'connection',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 74,
            'endLine' => 74,
            'startColumn' => 39,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get a schema builder instance for a connection.
 *
 * @param  string|null  $name
 * @return \\Illuminate\\Database\\Schema\\Builder
 */',
        'startLine' => 74,
        'endLine' => 77,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Schema',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Schema',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Schema',
        'aliasName' => NULL,
      ),
      'getFacadeAccessor' => 
      array (
        'name' => 'getFacadeAccessor',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the registered name of the component.
 *
 * @return string
 */',
        'startLine' => 84,
        'endLine' => 87,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Schema',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Schema',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Schema',
        'aliasName' => NULL,
      ),
    ),
    'traitsData' => 
    array (
      'aliases' => 
      array (
      ),
      'modifiers' => 
      array (
      ),
      'precedences' => 
      array (
      ),
      'hashes' => 
      array (
      ),
    ),
  ),
));