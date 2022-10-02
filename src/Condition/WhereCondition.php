<?php

declare(strict_types=1);

namespace Maxon755\DatabaseAssertion\Condition;

class WhereCondition
{
    /**
     * @var array<string>
     */
    private array $allowedComparisonOperations = [
        '=',
        '>',
        '<',
        '>=',
        '<=',
    ];

    private string $column;

    private string $operation;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @param mixed $value
     */
    public function __construct(string $column, string $operation, $value)
    {
        $this->assertOperationAllowed($operation);

        $this->column = $column;
        $this->value = $value;
        $this->operation = $operation;
    }

    /**
     * @param mixed $value
     */
    public static function make(string $column, string $operation, $value): self
    {
        return new self($column, $operation, $value);
    }

    public function getColumn(): string
    {
        return $this->column;
    }

    public function getOperation(): string
    {
        return $this->operation;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    private function assertOperationAllowed(string $operation): void
    {
        if (!\in_array($operation, $this->allowedComparisonOperations, true)) {
            throw new \InvalidArgumentException("Operation [{$operation}] not allowed");
        }
    }
}
