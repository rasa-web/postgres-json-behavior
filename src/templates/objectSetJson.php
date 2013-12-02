    /**
    * Set the value of [<?php echo $columnName ?>] column.
    *
    * @param  array $array new value
    * @return The   current object (for fluent API support)
    */
    public function set<?php echo ucfirst($columnName) ?>(array $data = array())
    {
        $this-><?php echo $columnName ?>AsArray = $data;
        $this-><?php echo $columnNameUnderscore ?> = json_encode($data);
        // There is no way supported by propel (in my knowledge) that we can set part of a column as modified.
        $this->modifiedColumns[] = <?php echo ucfirst($tableName) ?>Peer::<?php echo strtoupper($columnNameUnderscore) ?>;
        return $this;
    }

    /**
    * Set partial of a json
    *
    * @param string $path path to change or append
    * @param string $data data to set
    *
    * @return The   current object (for fluent API support)
    */
    public function set<?php echo ucfirst($columnName) ?>Path($path, $data)
    {
        if (!$this-><?php echo $columnName ?>AsArray || !is_array($this-><?php echo $columnName ?>AsArray)) {
            $this->initJsonFields();
        }

        $pathArray = explode('.', $path);
        $current = &$this-><?php echo $columnName ?>AsArray;
        while ($p = array_shift($pathArray)) {
            if (is_array($current) && array_key_exists($p, $current)) {
                $current = &$current[$p];
            } elseif (!is_array($current)) {
                throw new PropelException("Can not set $path in this json");
            } else {
                $current[$p] = array();
                $current = &$current[$p];
            }
        }
        $current = $data;
        $this->set<?php echo ucfirst($columnName) ?>($this-><?php echo $columnName ?>AsArray);
        return $this;
    }
