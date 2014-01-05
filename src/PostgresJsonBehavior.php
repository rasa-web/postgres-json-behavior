<?php

/**
 * Class PostgresJsonBehavior
 */
class PostgresJsonBehavior extends Behavior
{

    public function modifyTable()
    {
        foreach (explode(',', $this->getParameter('column_names')) as $column) {
            if ($this->getTable()->hasColumn($column)) {
                $columnObject = $this->getTable()->getColumn($column);
                $columnObject->getDomain()->replaceSqlType('json');
            }
        }
    }

    /**
     * @var PostgresJsonBehaviorObjectBuilderModifier
     */
    private $objectBuilderModifier;

    /**
     * @var PostgresJsonBehaviorQueryBuilderModifier
     */
    private $queryBuilderModifier;

    /**
     * {@inheritdoc}
     */
    public function getObjectBuilderModifier()
    {
        if (is_null($this->objectBuilderModifier)) {
            $this->objectBuilderModifier = new PostgresJsonBehaviorObjectBuilderModifier($this);
        }

        return $this->objectBuilderModifier;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryBuilderModifier()
    {
        if (null === $this->queryBuilderModifier) {
            $this->queryBuilderModifier = new PostgresJsonBehaviorQueryBuilderModifier($this);
        }

        return $this->queryBuilderModifier;
    }
}
