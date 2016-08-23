<?php namespace App\Data\Blog;

use Illuminate\Database\Eloquent\Model;
use Config;

class BaseModel extends Model
{

    /**
     * Allow for camelCased attribute access
     * @param mixed $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        return parent::getAttribute(snake_case($key));
    }

    /**
     * Allow for camelCased attribute set
     * @param mixed $key
     * @param mixed $value
     */
    public function setAttribute($key, $value)
    {
        parent::setAttribute(snake_case($key), $value);
    }

    /**
     * Return database table name
     */
    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    /**
     * @param string $field
     * @param string $format
     * @return mixed
     */
    public function printTime($field = 'created_at', $format = '')
    {
        if ($format) {
            $configFormat = Config::get('time.' . $format);
            if ($configFormat) {
                $format = $configFormat;
            }
        } else {
            $format = Config::get('time.default');
        }
        return $this->$field->format($format);
    }
}