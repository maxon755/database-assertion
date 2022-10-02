<?php

declare(strict_types=1);

namespace Maxon755\DatabaseAssertion;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder as DoctrineQueryBuilder;
use Maxon755\DatabaseAssertion\Condition\WhereCondition;

class QueryBuilder
{
    /**
     * @param array<mixed> $parameters
     */
    public static function buildCountQuery(Connection $connection, string $table, array $parameters): DoctrineQueryBuilder
    {
        $queryBuilder = $connection->createQueryBuilder();

        $queryBuilder
            ->select('count(*)')
            ->from($table)
        ;

        foreach ($parameters as $key => $value) {
            self::buildWhereCondition($queryBuilder, $key, $value);
        }

        return $queryBuilder;
    }

    /**
     * @param mixed      $value
     * @param int|string $key
     */
    private static function buildWhereCondition(DoctrineQueryBuilder $queryBuilder, $key, $value): void
    {
        if ($value instanceof WhereCondition) {
            $name = $value->getColumn();
            $operation = $value->getOperation();
            $value = $value->getValue();
        } else {
            $name = $key;
            $operation = '=';
        }

        $queryBuilder->andWhere("{$name} {$operation} :{$name}");
        $queryBuilder->setParameter($name, $value);
    }
}
