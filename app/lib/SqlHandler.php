<?php

class SqlHandler {

    private $connection;
    private $database;
    private $writer;
    private static $showQuery = false;
    private static $showAllQueries = false;

    function __construct(array $options) {
        $this->connection = mysql_connect($options['host'], $options['user'], $options['password']);
        if (!$this->connection) {
            throw new Exception('Not connected : ' . mysql_error());
        }

        // make foo the current db
        $this->database = mysql_select_db($options['database'], $this->connection);
        if (!$this->database) {
            throw new Exception("Can't use foo : " . mysql_error());
        }
        //becouse of 'ш' and 'И'
        mysql_query("SET NAMES 'UTF8'");
        $this->writer = new SqlWriter();
    }

    function query($query) {
        if(self::$showQuery === true){
            d($query);
            if(self::$showAllQueries != true)
                self::$showQuery = false;
        }
        if (!$result = mysql_query($query, $this->connection)) {
            throw new Exception('Invalid query: ' . mysql_error());
        }
        return $result;
    }

    function insert($table, $values) {
        $this->query($this->writer->insert($table, $values));

        return mysql_insert_id($this->connection);
    }

    function update($table, $options, $where = null) {
        return $this->query($this->writer->update($table, $options, $where));
    }

    function delete($table, $where = null) {
        return $this->query($this->writer->delete($table, $where));
    }

    function select($table, $options = array()) {
        $result = $this->query($this->writer->select($table, $options));
        $array = array();
        while ($row = mysql_fetch_assoc($result)) {
            $array[] = $row;
        }
        mysql_free_result($result);
        return $array;
    }

    function row($table, $options = array()) {
        $result = $this->query($this->writer->select($table, $options));
        $row = mysql_fetch_assoc($result);
        mysql_free_result($result);

        return $row;
    }

    function exists($table, $where = null) {
        return mysql_num_rows($this->query($this->writer->select($table, array(
                                    'where' => $where,
                                    'limit' => 1
                                )))) > 0;
    }

    function count($table, $where = null, $field = '*', $distinct = false) {
        if ($field != '*') {
            $field = '`' . mysql_real_escape_string($field) . '`';

            if ($distinct) {
                $field = 'DISTINCT ' . $field;
            }
        }

        $row = $this->row($table, array(
            'fields' => "COUNT({$field}) AS the_count",
            'where' => $where
                ));

        return $row['the_count'];
    }

    function get($table, $id) {
        if (!$id = (int) $id) {
            return null;
        }

        return $this->row($table, array(
                    'where' => array('id' => $id),
                    'limit' => 1
                ));
    }

    public static function showQuery($onlyNext = true, $all = false) {
        if ($onlyNext)
            self::$showQuery = true;

        if ($all)
            self::$showAllQueries = true;
    }

}