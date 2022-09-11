# Database Assertions

Provides laravel-like database assertions for integration testing

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
```

Uses default doctrine connection `'doctrine.dbal.default_connection'`
