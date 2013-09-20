<?php
namespace Rest\Modules;

/**
 * User: xavier
 * Date: 7/24/12
 * Time: 3:40 PM
 */
class OptionFields
    implements \Rest\Controller
{
    const MODULE_OPT_FIELDS = "module_optfields";

    /**
     * Will set the module_opt_extended variable (array) as a parameter of the server
     * @param \Rest\Server $server
     * @return \Rest\Server
     */
    function execute(\Rest\Server $server)
    {
        //  get the request URL parameters and find the opt_extended array
        $fields = $server->getRequest()->getGet("opt_fields");
        if( !empty($fields) )
        {
            $options = $this->__explodePath($fields, ",");
            $server->setParameter(self::MODULE_OPT_FIELDS, $options);
        }
        return $server;
    }

    /**
     * @param string $fields
     * @param string $delimiter
     * @return array
     */
    private function __explodePath($fields = "", $delimiter = ",")
    {
        $array = explode($delimiter, $fields);
        $array = array_combine($array, $array);

        // to support value.property paths to one level...
        // for each item in the array, see if it contains a sub property
        // if it does set the base property as an array if not already
        // and push the sub property into it
        foreach($array as $item)
        {
            $item_split = explode(".", $item);

            if (count($item_split) == 2)
            {
                $base_property = $item_split[0];
                $property_value = $item_split[1];

                if (!is_array($array[$base_property]))
                {
                    $array[$base_property] = array();
                }

                $array[$base_property][$property_value] = $property_value;

                unset($array[$item]);
            }
        }

        return $array;
    }


    /**
     * figures out, based on the provided opt_fields and known mandatory fields
     * and optional fields, what actual fields will be processed
     * @static
     * @param array $requested_fields
     * @param array $mandatory_fields
     * @param array $standard_fields
     * @param bool $compact
     * @return array
     */
    static public function parse($requested_fields = [], $mandatory_fields = [], $standard_fields = [], $compact = true)
    {
        $mandatory_fields = array_combine($mandatory_fields, $mandatory_fields);
        $standard_fields = array_combine($standard_fields, $standard_fields);

        // set the default return value as the mandatory fields
        $results = $mandatory_fields;

        // if not optional fields have been defined
        if (count($requested_fields) == 0)
        {
            // if not compact, we have just the mandatory ones
            if (!$compact)
            {
                // add to the result all the standard fields as well
                $results = array_merge($results, $standard_fields);
            }
        }
        else
        {
            // if the "all" field is defined
            $show_all = isset($requested_fields["all"]);
            if ($show_all === true)
            {
                // inject the requested and standard fields into the mandatory set already defined
                $results = array_merge($results, $standard_fields);
                $results = array_merge($results, $requested_fields);
            }
            else
            {
                // if we are picking requested fields verify the $requested_fields against the $standard_fields
                $verified_fields = array();
                foreach ($requested_fields as $field => $value)
                {
                    // take the performance advantage of isset() while keeping the NULL element correctly detected
                    if (isset($standard_fields[$field]) || array_key_exists($field, $standard_fields))
                    {
                        $verified_fields[$field] = $value;
                    }
                }

                // combine the verified_fields with the mandatory set already defined
                $results = array_merge($results, $verified_fields);

                // if the list of results equal the count of mandatory fields
                // and we aren't getting the compact version
                if (count($results) == count($mandatory_fields) && !$compact)
                {
                    // just return the mandatory set already defined and standard fields
                    $results = array_merge($results, $standard_fields);
                }
            }
        }

        return $results;
    }
}
