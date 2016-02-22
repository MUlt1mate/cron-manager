<?php

/**
 * This class provides access to DB.
 * It's not a part of main library, just an example.
 * And it's pretty poor. Please consider use your own implementation.
 * @property CI_DB_query_builder $db
 */
class DbBaseModel extends CI_Model
{
    private $new_record = false; // whether this instance is new or not
    private $attributes = array(); // attribute name => attribute value
    private $errors = array();

    public static $table_name;
    public static $primary_key;

    public function __construct()
    {
        parent::__construct();
        $this->new_record = true;
        foreach ($this->attributes() as $key) {
            $this->attributes[$key] = '';
        }
    }

    public function __get($name)
    {
        $value = $this->getAttribute($name);
        if (!is_null($value)) {
            return $value;
        }
        return parent::__get($name);
    }

    public function __set($name, $value)
    {
        $this->setAttribute($name, $value);
    }

    public function __isset($name)
    {
        return isset($this->attributes[$name]);
    }

    public function attributes()
    {
        return array();
    }

    public static function findOne($condition = array())
    {
        $query = self::query($condition)->get();
        if ($query->num_rows() > 0) {
            $class_name = get_called_class();
            $model = new $class_name;
            /**
             * @var self $model
             */
            $model->setAttributes($query->first_row('array'));
            $model->setIsNewRecord(false);
            return $model;
        }
        return false;
    }

    /**
     * @param $value
     * @return self|false
     */
    public static function findByPk($value)
    {
        return self::findOne(array('where' => array(static::$primary_key => $value)));
    }

    public static function findAll($condition = array())
    {
        $found = array();
        $query = self::query($condition)->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $class_name = get_called_class();
                $model = new $class_name;
                /**
                 * @var self $model
                 */
                $model->setAttributes($row);
                $model->setIsNewRecord(false);
                $found[] = $model;
            }
        }

        return $found;
    }

    public static function findAllBySql($sql, $binds = null)
    {
        $found = array();
        $ci =& get_instance();
        $query = $ci->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $class_name = get_called_class();
                $model = new $class_name;
                /**
                 * @var self $model
                 */
                $model->setAttributes($row);
                $model->setIsNewRecord(false);
                $found[] = $model;
            }
        }

        return $found;
    }

    public static function query($condition)
    {
        $ci =& get_instance();
        $ci->db->from(static::$table_name);

        if (!array_key_exists('where', $condition)) {
            $found = false;
            foreach (array('select', 'order', 'limit', 'like', 'in', 'not_in') as $key) {
                if (array_key_exists($key, $condition)) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $condition = array('where' => $condition);
            }
        }

        $select = empty($condition['select']) ? '*' : $condition['select'];
        $ci->db->select($select);

        if (!empty($condition['where'])) {
            $ci->db->where($condition['where']);
        }

        if (!empty($condition['like'])) {
            $ci->db->like($condition['like'][0], $condition['like'][1]);
        }


        if (!empty($condition['in'])) {
            $ci->db->where_in($condition['in'][0], $condition['in'][1]);
        }

        if (!empty($condition['not_in'])) {
            $ci->db->where_not_in($condition['not_in'][0], $condition['not_in'][1]);
        }

        if (!empty($condition['order'])) {
            $ci->db->order_by($condition['order'][0], isset($condition['order'][1]) ? $condition['order'][1] : 'ASC');
        }

        if (!empty($condition['limit'])) {
            $offset = !empty($condition['offset']) ? $condition['offset'] : 0;
            $ci->db->limit((int)$condition['limit'], (int)$offset);
        }

        return $ci->db;
    }

    protected static function getDb()
    {
        $ci =& get_instance();
        return $ci->db;
    }

    public function setAttributes($set)
    {
        foreach ($this->attributes() as $key) {
            if (isset($set[$key])) {
                $this->setAttribute($key, $set[$key]);
            }
        }
    }

    public function setAttribute($name, $value)
    {
        if (property_exists($this, $name)) {
            $this->$name = $value;
            return true;
        } elseif (array_key_exists($name, $this->attributes)) {
            $this->attributes[$name] = $value;
            return true;
        }

        return false;
    }

    public function getAttribute($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        } elseif (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }
        return null;
    }

    public function getAttributes()
    {
        $data = array();
        foreach ($this->attributes() as $name) {
            $data[$name] = $this->getAttribute($name);
        }

        return $data;
    }

    protected function setIsNewRecord($set)
    {
        $this->new_record = $set;
    }

    public function isNewRecord()
    {
        return $this->new_record;
    }

    public function save()
    {
        if ($this->beforeSave()) {
            $result = $this->isNewRecord() ? $this->insert() : $this->update();
            if ($result) {
                return $this->afterSave();
            }
        }
        return false;
    }

    protected function insert()
    {
        $insert = $this->attributes;
        unset($insert[static::$primary_key]);

        $this->db->insert(static::$table_name, $insert);

        if (false === ($new_id = $this->db->insert_id())) {
            return false;
        }

        $this->setAttribute(static::$primary_key, $new_id);
        $this->setIsNewRecord(false);

        return true;
    }

    protected function update()
    {
        $update = $this->attributes;
        $key = static::$primary_key;
        unset($update[$key]);
        return (bool)$this->db->where(array($key => $this->$key))->update(static::$table_name, $update);
    }

    protected function beforeSave()
    {
        return true;
    }

    protected function afterSave()
    {
        return true;
    }

    /**
     * @param string $key
     * @param string $message
     */
    public function setError($key, $message = '')
    {
        $this->errors[$key] = $message;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getError($key)
    {
        return isset($this->errors[$key]) ? $this->errors[$key] : false;
    }

    public function delete()
    {
        if ($this->isNewRecord()) {
            return false;
        }

        if (false === $this->beforeDelete()) {
            return false;
        }

        $key = static::$primary_key;
        $this->db->delete(static::$table_name, $key . '=' . $this->$key, 1);

        return $this->afterDelete();
    }

    protected function beforeDelete()
    {
        return true;
    }

    protected function afterDelete()
    {
        return true;
    }
}
