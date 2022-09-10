<?php

declare(strict_types=1);

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\PDO\MySQL\Driver;
use Maxon755\DatabaseAssertion\DataBaseAssertions;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \Maxon755\DatabaseAssertion\DataBaseAssertions
 */
final class DatabaseAssertionsTest extends TestCase
{
    public function testAssertDatabaseHasThrowsExceptionOnEmptySet(): void
    {
        $testCase = new class() {
            use DataBaseAssertions;

            /**
             * @param \Doctrine\DBAL\Query\QueryBuilder $queryBuilder
             *
             * @return array<mixed>
             */
            private function getRows(Doctrine\DBAL\Query\QueryBuilder $queryBuilder): array
            {
                return [];
            }

            private function getConnection(): Connection
            {
                return new Connection([], new Driver());
            }
        };

        $this->expectException(ExpectationFailedException::class);

        $testCase->assertDatabaseHas('table', [
            'column1' => 'value1',
            'column2' => 'value2',
        ]);
    }

    public function testAssertDatabaseMissingThrowsExceptionOnNonEmptySet(): void
    {
        $testCase = new class() {
            use DataBaseAssertions;

            /**
             * @param \Doctrine\DBAL\Query\QueryBuilder $queryBuilder
             *
             * @return array<array<string, mixed>>
             */
            private function getRows(Doctrine\DBAL\Query\QueryBuilder $queryBuilder): array
            {
                return [
                    [
                        'column1' => 'value1',
                        'column2' => 'value2',
                    ],
                ];
            }

            private function getConnection(): Connection
            {
                return new Connection([], new Driver());
            }
        };

        $this->expectException(ExpectationFailedException::class);

        $testCase->assertDatabaseMissing('table', [
            'column1' => 'value1',
            'column2' => 'value2',
        ]);
    }
}
