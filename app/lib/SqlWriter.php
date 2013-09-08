<?php


class SqlWriter {
	private function quote($string){
		return '"' . mysql_real_escape_string($string) . '"';
	}
	
	private function quote2($string){
		return '`' . mysql_real_escape_string($string) . '`';
	}

	private function escape($string){
		return mysql_real_escape_string($string);
	}
	
	private function join($options, $glue){
		$values = array();
		foreach((array) $options as $key => $value){
                    $separator = '=';
                    if(is_array($value)){
                        foreach($value as $keyy => $valuee){
                            $separator = $keyy;
                            $value = $valuee;
                        }
                    }

                    $values[] = is_numeric($key) ? $value : $this->quote2($key) .$separator. $this->quote($value);
		}
		return join($glue, $values);
	}

	function insert($table, $options){
		$fields = array();
		$values = array();
                
		foreach($options as $key => $value){
			$fields[] = $this->quote2($key);
			$values[] = $this->quote($value);
		}
                
		$fields = join(', ', $fields); 
		$values = join(', ', $values);
                //d("INSERT INTO {$table} ( {$fields} ) VALUES ( {$values} )");
		return "INSERT INTO {$table} ( {$fields} ) VALUES ( {$values} )";
	}

	function update($table, $options, $where = null){
                //d("UPDATE {$table} SET " . $this->join($options, ', ') . ' ' . $this->where($where));
		return "UPDATE {$table} SET " . $this->join($options, ', ') . ' ' . $this->where($where);
	}

	function delete($table, $where = null){
		return "DELETE FROM {$table} " . $this->where($where);
	}

	// options: fields, where, sort, limit
	function select($table, $options = array()){
		$fields	 = array_cut($options, 'fields', '*');
		$fields  = is_array($fields) ? array_map(array($this, 'quote2'), join(', ', $fields)) : $fields;
		
		$sql = array('SELECT',  $fields, 'FROM', $table);
		
		if (!empty($options['where']))	$sql[] = $this->where($options['where']);
                if (!empty($options['whereOr']))	$sql[] = $this->whereOr($options['whereOr']);
		if (!empty($options['sort']))	$sql[] = 'ORDER BY ' . $options['sort'];
		if (!empty($options['limit']))	$sql[] = 'LIMIT ' . $options['limit'];
                //d(join(' ', $sql));
                return join(' ', $sql);
	}

	function where($conditions = null){
		if (empty($conditions)){
			return '';
		}

                return 'WHERE ' . $this->join($conditions, ' AND ');
	}
        
        function whereOr($conditions = null){
		if (empty($conditions)){
			return '';
		}
                
		return 'WHERE ' . $this->join($conditions, ' OR ');
	}
}
