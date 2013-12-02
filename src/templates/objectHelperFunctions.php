

    /**
     * Convert hstore string to array and initialize all json variables.
     *
     * @return void
     */
    protected function initJsonFields()
    {
        $columns = <?php var_export($columnNames) ?>;
        foreach ($columns as $columnNameUnderscore => $columnName) {
            $property = "{$columnName}AsArray";
            if (null === $this->$property && null !== $this->$columnNameUnderscore) {
                $jsonArray = json_decode($this->{$columnNameUnderscore}, true);
                $this->$property = $jsonArray;
            }
        }
    }
