# Database Assertions

Provides laravel-like database assertions for integration testing of symfony projects

Uses default doctrine connection `'doctrine.dbal.default_connection'

You are welcome to contribute

### Installation
```
composer require maxon755/database-assertion --dev
```

### Usage

Check database has rows
```php
    use DataBaseAssertions;

    $testCase->assertDatabaseHas('table', [
        'column1' => 'value1',
        'column2' => 'value2',
    ]);
```

Check rows are missing in database
```php
    use DataBaseAssertions;

    $testCase->assertDatabaseMissing('table', [
        'column1' => 'value1',
        'column2' => 'value2',
    ]);
````
