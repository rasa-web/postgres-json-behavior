<?php

/**
 * Class PostgresJsonBehaviorObjectBuilderModifier
 */
class PostgresJsonBehaviorObjectBuilderModifier
{
    /**
     * @var PostgresJsonBehavior
     */
    protected $behavior;

    public function __construct(Behavior $behavior)
    {
        $this->behavior = $behavior;
    }

    /**
     * {@inheritdoc}
     */
    public function objectAttributes($builder)
    {
        return $this->behavior->renderTemplate(
            'objectAttributes',
            $this->getTemplateData()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function objectMethods($builder)
    {
        $script = $this->behavior->renderTemplate('objectHelperFunctions', $this->getTemplateData());

        return $script;
    }

    /**
     * {@inheritdoc}
     */
    public function objectFilter(&$script)
    {
        $columnNames = $this->behavior->getParameter('column_names');
        $columnNames = explode(',', $columnNames);
        $newName = array();
        foreach ($columnNames as $columnName) {
            $newName[$columnName] = ucfirst($this->camelize($columnName));
        }

        $parser = new PropelPHPParser($script, true);

        foreach ($newName as $columnNameUnderscore => $column) {
            $parser->replaceMethod(
                'get' . $column,
                $this->behavior->renderTemplate(
                    'objectGetJson',
                    array_merge($this->getTemplateData(), array('columnName' => lcfirst($column)))
                )
            );
            $parser->replaceMethod(
                'set' . $column,
                $this->behavior->renderTemplate(
                    'objectSetJson',
                    array_merge(
                        $this->getTemplateData(),
                        array(
                            'columnName' => lcfirst($column),
                            'columnNameUnderscore' => $columnNameUnderscore
                        )
                    )
                )
            );
        }
        $script = $parser->getCode();
    }

    private function getTemplateData()
    {
        $columnNames = $this->behavior->getParameter('column_names');
        $columnNames = explode(',', $columnNames);
        $newName = array();
        foreach ($columnNames as $columnName) {
            $newName[$columnName] = lcfirst($this->camelize($columnName));
        }

        return array(
            'tableName' => $this->behavior->getTable()->getName(),
            'phpTableName' => $this->behavior->getTable()->getPhpName(),
            'columnNames' => $newName,
            'exception' => $this->behavior->getParameter('exception_on_not_found')
        );
    }

    private function camelize($string)
    {
        return lcfirst(str_replace(' ', '', ucwords(strtr($string, '_-', '  '))));
    }
}
