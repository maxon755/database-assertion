<?php

declare(strict_types=1);

namespace Maxon755\DatabaseAssertion\Constraint;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use JsonException;
use Maxon755\DatabaseAssertion\Condition\WhereCondition;
use Maxon755\DatabaseAssertion\QueryBuilder;
use PHPUnit\Framework\Constraint\Constraint;

class PresentInDatabase extends Constraint
{
    private Connection $connection;

    /**
     * @var array<string, mixed>
     */
    private array $parameters;

    /**
     * @param array<string, mixed> $parameters
     */
    public function __construct(Connection $connection, array $parameters)
    {
        $this->connection = $connection;
        $this->parameters = $parameters;
    }

    /**
     * @param string $table
     *
     * @throws Exception
     */
    public function matches($table): bool
    {
        return QueryBuilder::buildCountQuery($this->connection, $table, $this->parameters)->fetchOne() > 0;
    }

    /**
     * @param string $table
     *
     * @throws JsonException
     */
    public function failureDescription($table): string
    {
        return sprintf(
            "a row in the table [%s] matches the attributes %s\n",
            $table,
            $this->toString()
        );
    }

    /**
     * @throws JsonException
     */
    public function toString(): string
    {
        $parameters = [];

        foreach ($this->parameters as $key => $value) {
            if ($value instanceof WhereCondition) {
                $parameters[] = [$value->getColumn(), $value->getOperation(), $value->getValue()];

                continue;
            }

            $parameters[] = [$key, '=', $value];
        }

        return json_encode($parameters, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    }
}
