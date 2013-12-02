
    /**
     * Filter the query on the <?php echo $columnName ?> column
     *
     * Example usage:
     * <code>
     * $query->filterBy<?php echo ucfirst($columnName) ?>Path('foo.name', 'bar'); // json -> '#>>{foo,name}' = 'bar'
     * $query->filterBy<?php echo ucfirst($columnName) ?>Path('foo.name', '%ar'); // json -> '#>>{foo,name}' = '%ar'
     * $query->filterBy<?php echo ucfirst($columnName) ?>Path('foo.name', 123, Criteria::GREATER_EQUAL); // json -> '#>>{foo,name}' >= '123'
     * </code>
     *
     * @param string $path       json path, use dot to separate path
     * @param string $value      value
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return BookQuery The current query, for fluid interface
     */
    public function filterBy<?php echo ucfirst($columnName) ?>Path($path = null, $value = null, $comparison = null)
    {
        if (null === $comparison) {
            if (preg_match('/[\%\*]/', $value)) {
                $value = str_replace('*', '%', $value);
                $comparison = Criteria::LIKE;
            } else {
                $comparison = Criteria::EQUAL;
            }
        }
        $pathArray = explode('.', $path);
        $path = join(',', $pathArray);
        return $this->where(
            sprintf("%s#>>'{%s}' %s ?",<?php echo $phpTableName ?>Peer::<?php echo strtoupper($columnNameUnderscore) ?>, $path, $comparison),
            $value,
            PDO::PARAM_STR
        );
    }
