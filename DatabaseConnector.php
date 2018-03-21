<?php
/**
 *
 * Purpose: Database Connector another way to connect database and coding standard
 * File Name: DatabaseConnector.php
 * Class Name: DatabaseConnector
 * Author: Anthony Payumo
 * Email: 1010payumo@yahoo.com
 * Git Repo: github.com/arpcats
 *
 */

class DatabaseConnector{

    public function __construct()
    {
        /* Get Config Instance * */
        $conf = Config::get_instance();

        /* Check where the variables are correct* */
        if (isset($conf->host) && isset($conf->username) && isset($conf->password))
        {
            $conn = new mysqli($conf->host, $conf->username, $conf->password, $conf->database);
            if($conn->connect_error)
            {
                die("Connection failed : " . $conn->connect_error);
            }
            else
            {
                $this->db = $conn;
            }
        }
    }

    public function select($table, $conditions = array())
    {
        $sql = 'SELECT ';
        $sql .= array_key_exists("select", $conditions) ? $conditions['select'] : '*';
        $sql .= ' FROM '.$table;

        if(array_key_exists("where", $conditions))
        {
            $sql .= ' WHERE ';
            $ctr = 0;
            foreach($conditions['where'] as $key => $val)
            {
                $optr = ($ctr > 0) ? ' AND ' : '';
                $sql .= $optr."`".$key."` = ". sprintf("'%s'", $this->db->real_escape_string($val));
                $ctr++;
            }
        }

        if(array_key_exists("like", $conditions))
        {
            $sql .= ' LIKE ';
            $ctr = 0;
            foreach($conditions['like'] as $key => $val)
            {
                $optr = ($ctr > 0) ? ' OR ' : '';
                $sql .= $optr."`".$key."` = ". sprintf("'%s'", $this->db->real_escape_string($val));
                $ctr++;
            }
        }

        if(array_key_exists("order_by", $conditions))
        {
            $sql .= ' ORDER BY '.$conditions['order_by'];
        }

        if(array_key_exists("offset", $conditions) && array_key_exists("limit", $conditions))
        {
            $sql .= ' LIMIT '.$conditions['offset'].', '.$conditions['limit'];
        }
        else if(array_key_exists("limit", $conditions))
        {
            $sql .= ' LIMIT '.$conditions['limit'];
        }

        $result = $this->db->query($sql);

        if(array_key_exists("return_type", $conditions))
        {
            if($conditions['return_type'] == 'count')
                $data = $result->num_rows;
            else if($conditions['return_type'] == 'single')
                $data = $result->fetch_assoc();
        }
        else
        {
            if($result->num_rows > 0)
            {
                while($rows = $result->fetch_assoc())
                {
                    $data[] = $rows;
                }
            }
        }

        return !empty($data) ? $data : false;
    }

    public function insert($table, $data)
    {
        if(!empty($data) && is_array($data))
        {
            $columns = '';
            $values  = '';
            $ctr = 0;

            foreach($data as $key => $val)
            {
                $pre = ($ctr > 0) ? ', ' : '';
                $columns .= $pre."`".$key."`";
                $values .= $pre.sprintf("'%s'", $this->db->real_escape_string($val));
                $ctr++;
            }

            $query = "INSERT INTO ".$table." (".$columns.") VALUES (".$values.")";
            $insert = $this->db->query($query);
            return $insert ? $this->db->insert_id : false;
        }
        else
        {
            return false;
        }
    }

    public function update($table, $data, $conditions)
    {
        if(!empty($data) && is_array($data))
        {
            $colSetValue = '';
            $where = '';
            $ctr = 0;

            foreach($data as $key => $val)
            {
                $pre = ($ctr > 0) ? ', ' : '';
                $colSetValue .= $pre.$key."='".$val."'";
                $ctr++;
            }

            if(!empty($conditions)&& is_array($conditions))
            {
                $where .= ' WHERE ';
                $ctr = 0;
                foreach($conditions as $key => $val)
                {
                    $pre = ($ctr > 0) ? ' AND ' : '';
                    $where .= $pre."`".$key."` = ".sprintf("'%s'", $this->db->real_escape_string($val));
                    $ctr++;
                }
            }

            $query = "UPDATE ".$table." SET ".$colSetValue.$where;
            $update = $this->db->query($query);
            return $update ? $this->db->affected_rows : false;
        }
        else
        {
            return false;
        }
    }

    public function delete($table, $conditions)
    {
        $where = '';
        if(!empty($conditions) && is_array($conditions))
        {
            $where .= ' WHERE ';
            $ctr = 0;
            foreach($conditions as $key => $val)
            {
                $pre = ($ctr > 0) ? ' AND ' : '';
                $where .= $pre.$key." = '".$val."'";
                $ctr++;
            }
        }

        $query = "DELETE FROM ".$table.$where;
        $delete = $this->db->query($query);
        return $delete ? true : false;
    }
}