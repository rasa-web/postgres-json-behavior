<?php

/**
 * Class PostgresJsonBehaviorQueryBuilderModifier
 */
class PostgresJsonBehaviorQueryBuilderModifier
{
    /**
     * @var PostgresJsonBehavior
     */
    protected $behavior;

    /**
     * constructor
     *
     * @param Behavior $behavior behavior to use
     */
    public function __construct(Behavior $behavior)
    {
        $this->behavior = $behavior;
    }


    /**
     * {@inheritdoc}
     */
    public function queryFilter(&$script)
    {
        $columnNames = $this->behavior->getParameter('column_names');
        $columnNames = explode(',', $columnNames);
        $parser = new PropelPHPParser($script, true);
        foreach ($columnNames as $columnNameUnderscore) {
            $columnName = ucfirst($this->camelize($columnNameUnderscore));
            $parser->addMethodAfter(
                'filterBy' . $columnName,
                $this->behavior->renderTemplate(
                    'queryFilterByJsonKey',
                    array(
                        'tableName' => $this->behavior->getTable()->getName(),
                        'columnName' => lcfirst($columnName),
                        'columnNameUnderscore' => $columnNameUnderscore,
                        'exception' => $this->behavior->getParameter('exception_on_not_found')
                    )
                )
            );
        }
        $script = $parser->getCode();
    }


    private function camelize($string)
    {
        return lcfirst(str_replace(' ', '', ucwords(strtr($string, '_-', '  '))));
    }

}
