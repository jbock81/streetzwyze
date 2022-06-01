<?php

namespace models\utils;

/**
 * Description of ArrayToXML
 *
 * @author intelWorX
 */
class ArrayToXML {

    /**
     * The main function for converting to an XML document.
     * Pass in a multi dimensional array and this recrusively loops through and builds up an XML document.
     *
     * @param array $data
     * @param string $rootNodeName - what you want the root node to be - defaultsto data.
     * @param SimpleXMLExtended $xml - should only be used recursively
     * @return string XML
     */
    public static function toXML($data, $rootNodeName = 'ResultSet', &$xml = null) {
        // var_dump($data); exit();
        // turn off compatibility mode as simple xml throws a wobbly if you don't.
        if (ini_get('zend.ze1_compatibility_mode') == 1) {
            ini_set('zend.ze1_compatibility_mode', 0);
        }
        if (is_null($xml)) {
            $xml = simplexml_load_string("<$rootNodeName />", __NAMESPACE__ . "\\SimpleXMLExtended");
            $rootNodeName = ArrayToXMLUtils::depluralize($rootNodeName);
        }
        // loop through the data passed in.
        foreach ($data as $key => $value) {
            //  if( $key == 'description')
            // no numeric keys in our xml please!
            $numeric = 0;
            $numericKey = "";
            if (is_numeric($key)) {
                $numeric = 1;
                $numericKey = $key;
                $key = $rootNodeName;
            }

            // delete any char not allowed in XML element names
            $key = preg_replace('/[^a-z0-9\-\_\.\:]/i', '', $key);

            // if there is another array found recursively call this function
            if (is_array($value)) {

                $node = $xml->addChild($key);
                //$node->addAttribute("id", );
                $key = ArrayToXMLUtils::depluralize($key);
                if (ArrayToXML::isAssoc($value)) {
                    //$node->addAttribute("id", $key);
                    //$node = $xml->addChild($key);
                }

                if ($numeric) {
                    $node->addAttribute("id", $numericKey);
                    $key = 'anon';
                }
                //$node = ( ArrayToXML::isAssoc($value) || $numeric ) ? $xml->addChild($key) : $xml;
                // recursive call.
                ArrayToXML::toXml($value, $key, $node);
            } else {

                // add single node.
                //$value = htmlentities(html_entity_decode($value), ENT_COMPAT | ENT_SUBSTITUTE, 'UTF-8');
                if (preg_match('/[^A-Za-z0-9_.\-\\/@\s\t().,\]\[;+:]/', $value)) {
                    $xml->addChild($key, '')->addCData($value);
                } else {
                    //echo $key," =>", $value, "<br/>";
                    //$xml->$key = $value; //addChild($key, $value);
                    $xml->addChild($key, $value);
                }
            }
        }

        // $xml->asXML('op.xml');
        // pass back as XML
        return $xml->asXML();

        // if you want the XML to be formatted, use the below instead to return the XML
        //$doc = new DOMDocument('1.0');
        //$doc->preserveWhiteSpace = false;
        //$doc->loadXML( $xml->asXML() );
        //$doc->formatOutput = true;
        //return $doc->saveXML();
    }

    /**
     * Convert an XML document to a multi dimensional array
     * Pass in an XML document (or SimpleXMLElement object) and this recrusively loops through and builds a representative array
     *
     * @param string $xml - XML document - can optionally be a SimpleXMLElement object
     * @return array ARRAY
     */
    public static function toArray($xml) {
        if (is_string($xml)) {
            $xml = new \SimpleXMLElement($xml);
        }
        $children = $xml->children();
        if (!$children) {
            return (string) $xml;
        }
        $arr = array();
        foreach ($children as $key => $node) {
            $node = ArrayToXML::toArray($node);

            // support for 'anon' non-associative arrays
            if ($key == 'anon') {
                $key = count($arr);
            }

            // if the node is already set, put it into an array
            if (array_key_exists($key, $arr) && isset($arr[$key])) {
                if (!is_array($arr[$key]) || !array_key_exists(0, $arr[$key]) || ( array_key_exists(0, $arr[$key]) && ($arr[$key][0] == null))) {
                    $arr[$key] = array($arr[$key]);
                }
                $arr[$key][] = $node;
            } else {
                $arr[$key] = $node;
            }
        }
        return $arr;
    }

    public static function xml2array($contents, $get_attributes = 1, $priority = 'tag') {
        if (!$contents) {
            return array();
        }

        if (!function_exists('xml_parser_create')) {
            //print "'xml_parser_create()' function not found!";
            return array();
        }

        //Get the XML parser of PHP - PHP must have this module for the parser to work
        $parser = xml_parser_create('');
        xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, trim($contents), $xml_values);
        xml_parser_free($parser);

        if (!$xml_values) {
            return;
        } //Hmm...
//Initializations
        $xml_array = array();
        $parents = array();
        $opened_tags = array();
        $arr = array();

        $current = &$xml_array; //Refference
        //Go through the tags.
        $repeated_tag_index = array(); //Multiple tags with same name will be turned into an array
        foreach ($xml_values as $data) {
            unset($attributes, $value); //Remove existing values, or there will be trouble
            //This command will extract these variables into the foreach scope
            // tag(string), type(string), level(int), attributes(array).
            extract($data); //We could use the array by itself, but this cooler.

            $result = array();
            $attributes_data = array();

            if (isset($value)) {
                if ($priority == 'tag') {
                    $result = $value;
                } else {
                    $result['value'] = $value;
                } //Put the value in a assoc array if we are in the 'Attribute' mode
            }

            //Set the attributes too.
            if (isset($attributes) and $get_attributes) {
                foreach ($attributes as $attr => $val) {
                    if ($priority == 'tag') {
                        $attributes_data[$attr] = $val;
                    } else {
                        $result['attr'][$attr] = $val;
                    } //Set all the attributes in a array called 'attr'
                }
            }

            //See tag status and do the needed.
            if ($type == "open") {//The starting of the tag '<tag>'
                $parent[$level - 1] = &$current;
                if (!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
                    $current[$tag] = $result;
                    if ($attributes_data) {
                        $current[$tag . '_attr'] = $attributes_data;
                    }
                    $repeated_tag_index[$tag . '_' . $level] = 1;

                    $current = &$current[$tag];
                } else { //There was another element with the same tag name
                    if (isset($current[$tag][0])) {//If there is a 0th element it is already an array
                        $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                        $repeated_tag_index[$tag . '_' . $level] ++;
                    } else {//This section will make the value an array if multiple tags with the same name appear together
                        $current[$tag] = array($current[$tag], $result); //This will combine the existing item and the new item together to make an array
                        $repeated_tag_index[$tag . '_' . $level] = 2;

                        if (isset($current[$tag . '_attr'])) { //The attribute of the last(0th) tag must be moved as well
                            $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                            unset($current[$tag . '_attr']);
                        }
                    }
                    $last_item_index = $repeated_tag_index[$tag . '_' . $level] - 1;
                    $current = &$current[$tag][$last_item_index];
                }
            } elseif ($type == "complete") { //Tags that ends in 1 line '<tag />'
                //See if the key is already taken.
                if (!isset($current[$tag])) { //New Key
                    $current[$tag] = $result;
                    $repeated_tag_index[$tag . '_' . $level] = 1;
                    if ($priority == 'tag' and $attributes_data) {
                        $current[$tag . '_attr'] = $attributes_data;
                    }
                } else { //If taken, put all things inside a list(array)
                    if (isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array...
                        // ...push the new element into that array.
                        $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;

                        if ($priority == 'tag' and $get_attributes and $attributes_data) {
                            $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                        }
                        $repeated_tag_index[$tag . '_' . $level] ++;
                    } else { //If it is not an array...
                        $current[$tag] = array($current[$tag], $result); //...Make it an array using using the existing value and the new value
                        $repeated_tag_index[$tag . '_' . $level] = 1;
                        if ($priority == 'tag' and $get_attributes) {
                            if (isset($current[$tag . '_attr'])) { //The attribute of the last(0th) tag must be moved as well
                                $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                                unset($current[$tag . '_attr']);
                            }

                            if ($attributes_data) {
                                $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                            }
                        }
                        $repeated_tag_index[$tag . '_' . $level] ++; //0 and 1 index is already taken
                    }
                }
            } elseif ($type == 'close') { //End of tag '</tag>'
                $current = &$parent[$level - 1];
            }
        }

        return($xml_array);
    }

    // determine if a variable is an associative array
    public static function isAssoc($array) {
        return (is_array($array) && 0 !== count(array_diff_key($array, array_keys(array_keys($array)))));
    }

}

class ArrayToXMLUtils {

    public static function depluralize($word) {
        // Here is the list of rules. To add a scenario,
        // Add the plural ending as the key and the singular
        // ending as the value for that key. This could be
        // turned into a preg_replace and probably will be
        // eventually, but for now, this is what it is.
        //
        // Note: The first rule has a value of false since
        // we don't want to mess with words that end with
        // double 's'. We normally wouldn't have to create
        // rules for words we don't want to mess with, but
        // the last rule (s) would catch double (ss) words
        // if we didn't stop before it got to that rule. 
        $rules = array(
            'ss' => false,
            'os' => 'o',
            'xes' => 'x',
            'oes' => 'o',
            //'ies' => 'y',
            'ves' => 'f',
            's' => '');
        // Loop through all the rules and do the replacement. 
        foreach (array_keys($rules) as $key) {
            // If the end of the word doesn't match the key,
            // it's not a candidate for replacement. Move on
            // to the next plural ending. 
            if (substr($word, (strlen($key) * -1)) != $key) {
                continue;
            }
            // If the value of the key is false, stop looping
            // and return the original version of the word. 
            if ($key === false) {
                return $word;
            }
            // We've made it this far, so we can do the
            // replacement. 
            return substr($word, 0, strlen($word) - strlen($key)) . $rules[$key];
        }
        return $word;
    }

    public static function pluralize($string) {
        $addIEs = array("o", "i", "s");
        if (strtolower($string) == 'was') {
            return 'were';
        }

        $lastChar = strtolower(substr($string, strlen($string) - 1, 1));

        if (in_array($lastChar, $addIEs)) {
            $endwith = "es";
        } elseif ($lastChar == "y") {
            $string = substr($string, 0, strlen($string) - 1);
            $endwith = "ies";
        } else {
            $endwith = "s";
        }

        return $string . $endwith;
    }

}

class SimpleXMLExtended extends \SimpleXMLElement {

    public function addCData($string) {
        $dom = dom_import_simplexml($this);
        $cdata = $dom->ownerDocument->createCDATASection($string);
        $dom->appendChild($cdata);
    }

}
