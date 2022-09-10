<?php

declare(strict_types=1);

namespace Maxon755\DatabaseAssertion;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use PHPUnit\Framework\Assert;
use RuntimeException;

trait DataBaseAssertions
{
    /**
     * @throws \Doctrine\DBAL\Exception
     */
    protected function assertDatabaseHas(string $table, array $parameters)
    {
        $queryBuilder = $this->buildQuery($table, $parameters);

        $rows = $this->getRows($queryBuilder);

        Assert::assertNotEmpty(
            $rows,
            'The result set is empty' . PHP_EOL .
            'SQL: ' . $queryBuilder->getSQL()
        );
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    protected function assertDatabaseMissing(string $table, array $parameters)
    {
        $queryBuilder = $this->buildQuery($table, $parameters);

        $rows = $this->getRows($queryBuilder);

        Assert::assertEmpty(
            $rows,
            'The result set IS NOT empty' . PHP_EOL .
            'SQL: ' . $queryBuilder->getSQL()
        );
    }

    private function buildQuery(string $table, array $parameters): QueryBuilder
    {
        $queryBuilder = $this->getConnection()->createQueryBuilder();

        $queryBuilder
            ->select('*')
            ->from($table)
        ;

        foreach ($parameters as $name => $value) {
            $queryBuilder->andWhere("{$name} = :{$name}");

            $queryBuilder->setParameter($name, $value);
        }

        return $queryBuilder;
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    private function getRows(QueryBuilder $queryBuilder): array
    {
        return $queryBuilder
            ->executeQuery()
            ->fetchAllAssociative()
        ;
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
