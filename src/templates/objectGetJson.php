    /**
     * Get the <?php echo ucfirst($columnName) ?> column value.
     * use dot (.) for path inside json like name.middle for {"name":{"middle":"value"}}
     *
     * @param string $path path to the wanted key, empty string for root
     *
    <?php
        if ($exception) {
            echo "     * @throws PropelException when the path is not available";
        }
    ?>
     * @return mixed
     */
    public function get<?php echo ucfirst($columnName) ?>($path = '', $default = null)
    {
        if (!$this-><?php echo $columnName ?>AsArray || !is_array($this-><?php echo $columnName ?>AsArray)) {
            $this->initJsonFields();
        }

        $current = $this-><?php echo $columnName ?>AsArray;
        $pathArray = explode('.', $path);
        while ($p = array_shift($pathArray)) {
            if (is_array($current) && array_key_exists($p, $current)) {
                $current = & $current[$p];
            } elseif (count($pathArray) == 0) {
                break;
            } else {
                <?php
                if ($exception) {
                    echo 'throw new PropelException("The $path is not exists in this json.")';
                } else {
                    echo 'return $default;';
                }
                ?>
            }
        }

        return $current;
    }
