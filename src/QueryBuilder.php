<?php

declare(strict_types=1);

namespace Maxon755\DatabaseAssertion;

use Doctrine\DBAL\Connection;

class QueryBuilder
{
    /**
     * @param array<mixed> $parameters
     */
    public static function buildCountQuery(Connection $connection, string $table, array $parameters): \Doctrine\DBAL\Query\QueryBuilder
    {
        $queryBuilder = $connection->createQueryBuilder();

        $queryBuilder
            ->select('count(*)')
            ->from($table)
        ;

        foreach ($parameters as $name => $value) {
            $queryBuilder->andWhere("{$name} = :{$name}");

            $queryBuilder->setParameter($name, $value);
        }

        return $queryBuilder;
    }
}
