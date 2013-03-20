<?php
namespace Csod\Rest;
/**
 * contains methods to massage an object for REST
 */
class ResourcePack
    extends \Csod\Db\ObjectPack
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var array
     */
    protected $_compactProperties = array();

    /**
     * @var array
     */
    protected $_standardProperties = array();

    /**
     *
     */
    public function __construct()
    {
        $reflect = new \ReflectionClass($this);
        $properties   = $reflect->getProperties();

        foreach ($properties as $property)
        {
            $docComment = $property->getDocComment();
            $name = $property->getName();

            if( preg_match('/@compact/', $docComment, $matches) )
            {
                array_push($this->_compactProperties, $name);
            }

            if( preg_match('/@standard/', $docComment, $matches) )
            {
                array_push($this->_standardProperties, $name);
            }
        }

        parent::__construct();
    }

    /**
     * returns a stdClass object representing the compact or standard form of this class
     * PLUS optional fields passed to it
     * @param bool $compact
     * @param null $opt_fields
     * @return \stdClass
     */
    public function filter($compact = true, $opt_fields = null)
    {
        $result = new \stdClass();

        // all sub objects are compact by default
        $compact_sub_objects = true;

        // get the actual properties we're going to return based on the optional fields being requested
        // and whether or not the resulting object is meant to be compact or not
        $opt_fields = \Diogok\Rest\Modules\OptionFields::parse($opt_fields, $this->_compactProperties, $this->_standardProperties, $compact);

        $reflect = new \ReflectionClass($this);
        $properties   = $reflect->getProperties();

        //  add them to _data if comment @data set
        foreach ($properties as $property)
        {
            $name = $property->getName();

            // take the performance advantage of isset() while keeping the NULL element correctly detected
            if (isset($opt_fields[$name]) || array_key_exists($name, $opt_fields))
            {
                // if the property is an array of other objects
                if (is_array($this->$name) || is_object($this->$name))
                {
                    if (is_array($this->$name))
                    {
                        $filtered_array = array();

                        $array_items = $this->$name;
                        foreach($array_items as $item)
                        {
                            if (is_object($item))
                            {
                                $item = $item->filter($compact_sub_objects, $opt_fields[$name]);
                            }
                            array_push($filtered_array, $item);
                        }

                        // reset the original array with the filtered set
                        $result->$name = $filtered_array;
                    }
                    else
                    {
                        $result->$name = $this->$name->filter($compact_sub_objects, $opt_fields[$name]);
                    }
                }
                else
                {
                    $result->$name = $this->{$name};
                }
            }
        }

        return $result;
    }

    /**/
}
?>
