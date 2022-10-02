<?php

declare(strict_types=1);

namespace Maxon755\DatabaseAssertion;

use Doctrine\DBAL\Connection;
use Maxon755\DatabaseAssertion\Constraint\PresentInDatabase;
use PHPUnit\Framework\Constraint\LogicalNot;
use RuntimeException;

trait DataBaseAssertions
{
    /**
     * @param array<string, mixed> $parameters
     */
    public function assertDatabaseHas(string $table, array $parameters): self
    {
        $this->assertThat(
            $table,
            new PresentInDatabase($this->getConnection(), $parameters)
        );

        return $this;
    }

    /**
     * @param array<string, mixed> $parameters
     */
    public function assertDatabaseMissing(string $table, array $parameters): self
    {
        $this->assertThat(
            $table,
            new LogicalNot(new PresentInDatabase($this->getConnection(), $parameters))
        );

        return $this;
    }

    /**
     * @throws RuntimeException
     */
    private function getConnection(): Connection
    {
        if (!method_exists($this, 'getContainer')) {
            $className = self::class;

            throw new RuntimeException(
                "Testcase {$className} doesn't have method `getContainer` to fetch the connection"
            );
        }

        if (!$this->getContainer()) {
            throw new RuntimeException("Can't get DI container");
        }

        $connection = $this->getContainer()->get('doctrine.dbal.default_connection');

        if (null === $connection) {
            throw new RuntimeException('Cant get database connection from DI');
        }

        return $connection;
    }
}
