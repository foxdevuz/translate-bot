<?php
    class DB_CONFIG
    {
        protected $conn;

        /**
         * @throws Exception
         */
        public function __construct($hostname, $username, $password, $databaseName)
        {
            $this->conn = new mysqli($hostname, $username, $password, $databaseName);
            if ($this->conn->connect_error) {
                throw new Exception("MYSQLI connect error: " . $this->conn->connect_error);
            }
        }

        /**
         * @return void
         */
        public function disconnect()
        {
            $this->conn->close();
        }

        protected function escapeString($value)
        {
            return $this->conn->real_escape_string($value);
        }

        public function selectAll($tableName)
        {
            $sqlQuery = "SELECT * FROM " . $this->escapeString($tableName);
            return $this->conn->query($sqlQuery);
        }

        public function selectWhere($tableName, $condition, $extra = "")
        {
            $sqlQuery = 'SELECT * FROM ' . $this->escapeString($tableName) . ' WHERE ';
            if (is_array($condition)) {
                foreach ($condition as $values) {
                    foreach ($values as $key => $value) {
                        if ($key !== 'cn') {
                            $sqlQuery .= $this->escapeString($key) . " " . $values['cn'] . "'";
                            $sqlQuery .= $this->escapeString($values[$key]) . "'";
                            $sqlQuery .= ' and ';
                        }
                    }
                }
                $sqlQuery = rtrim($sqlQuery, ' and ');
            } else {
                $sqlQuery .= $condition;
            }
            $sqlQuery .= $extra;
            return $this->conn->query($sqlQuery);
        }

        public function insertInto($tableName, $data = [])
        {
            $sqlQuery = 'INSERT INTO ' . $this->escapeString($tableName);
            $columns = '(';
            $values = "(";
            foreach ($data as $key => $value) {
                $columns .= $this->escapeString($key) . ',';
                $values .= "'" . $this->escapeString($value) . "',";
            }
            $columns = rtrim($columns, ',') . ')';
            $values = rtrim($values, ',') . ')';
            $sqlQuery .= $columns . ' VALUES ' . $values;
            return $this->conn->query($sqlQuery);
        }

        public function deleteWhere($tableName, $condition = [], $extra = "")
        {
            $sqlQuery = 'DELETE FROM ' . $this->escapeString($tableName) . ' WHERE ';
            foreach ($condition as $values) {
                foreach ($values as $key => $value) {
                    if ($key != 'cn') {
                        $sqlQuery .= $this->escapeString($key) . " " . $values['cn'] . "'";
                        $sqlQuery .= $this->escapeString($values[$key]) . "'";
                        $sqlQuery .= ' and ';
                    }
                }
            }
            $sqlQuery = rtrim($sqlQuery, ' and ') . $extra;
            return $this->conn->query($sqlQuery);
        }

        public function updateWhere($tableName, $values = [], $condition = [], $extra = "")
        {
            $sqlQuery = 'UPDATE ' . $this->escapeString($tableName) . ' SET ';
            foreach ($values as $key => $value) {
                $sqlQuery .= $this->escapeString($key) . "='" . $this->escapeString($value) . "',";
            }
            $sqlQuery = rtrim($sqlQuery, ',') . ' WHERE ';
            foreach ($condition as $keys => $val) {
                if ($keys != 'cn') {
                    $sqlQuery .= $this->escapeString($keys) . "='" . $this->escapeString($val) . "' and ";
                }
            }
            $sqlQuery = rtrim($sqlQuery, ' and ') . $extra;
            return $this->conn->query($sqlQuery);
        }

        public function withSqlQuery($query)
        {
            return $this->conn->query($this->escapeString($query));
        }

        public function withSqlQueryWithoutEscapeString($query)
        {
            return $this->conn->query($query);
        }

        public function fetch($result)
        {
            return $result->fetch_assoc();
        }
    }
