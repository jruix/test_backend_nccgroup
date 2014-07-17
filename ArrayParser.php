<?php

/**
 * Class ArrayParser
 * Parse an array in to other formats
 * Note: Another option to include indentation in the returned string is SimpleXML it would clarify the code a lot
 * it hasn't been used as I m not sure if it was considered a third party library as mentioned in the test description.
 */
class ArrayParser
{
    const OPEN_XML_TAG_START_CHAR = '<';
    const OPEN_XML_TAG_END_CHAR = '>';
    const CLOSE_XML_TAG_START_CHAR = '<';
    const CLOSE_XML_TAG_END_CHAR = '/>';

    const INDENTATION_SIZE = 4;
    const INDENTATION_CHAR = ' ';
    const NEW_LINE_CHAR = "\n";

    private $inputArray;
    private $xmlDocument = '';
    private $jsonDocument = '';

    private static $indentationDeep = 0;

    /**
     * @param Array|null $inputArray
     */
    public function __construct($inputArray = null)
    {
        $this->inputArray = $inputArray;
    }

    /**
     * @param Array $inputArray
     */
    public function setInputArray($inputArray)
    {
        $this->inputArray = $inputArray;

        //Remove cache
        unset($this->xmlDocument);
        unset($this->jsonDocument);
    }

    /**
     * Return the JSON version of the provided Array
     * @return null|string
     */
    public function getJsonDocument()
    {
        if (is_null($this->inputArray)) {
            return null;
        }
        if (! empty($this->jsonDocument) ) {

            return $this->jsonDocument;
        }

        $this->jsonDocument = json_encode($this->inputArray);

        return $this->jsonDocument;
    }

    /**
     * Return the XML version of the provided Array
     * @return null|string
     */
    public function getXmlDocument()
    {
        if (is_null($this->inputArray)) {
            return null;
        }
        if (! empty($this->xmlDocument) ) {

            return $this->xmlDocument;
        }

        $this->xmlDocument = $this->getXmlElement($this->inputArray);

        return $this->xmlDocument;
    }

    /**
     * Return the xml version of the array element and his content doing recursive calls to itself
     * @param Array|String $inputArray
     *
     * @return null|string
     * @throws Exception
     */
    private function getXmlElement($inputArray)
    {
        if (is_null($inputArray)) {

            return null;
        }

        if (empty($inputArray['name'])) {

            Throw new Exception('Malformed input array, name element is missing');
        }

        $xmlElement = '';
        $xmlElement .= $this->getXmlElementOpenTag($inputArray);
        $this->increaseIndentationIndex();


        foreach ($inputArray['children'] as $elementChild) {
            if (is_string($elementChild)) {
                $xmlElement .= $elementChild;
                $xmlElement .= $this->getXmlElementCloseTag($inputArray);
                $this->decreaseIndentationIndex();

                return $xmlElement;
            } elseif (is_array($elementChild)) {
                $xmlElement .= self::NEW_LINE_CHAR;
                $xmlElement .= $this->getCurrentIndentation();
                $xmlElement .= $this->getXmlElement($elementChild);

            } else {

                Throw new Exception('Malformed input array, children element is not an array or a string');
            }
        }
        $xmlElement .= self::NEW_LINE_CHAR;
        $this->decreaseIndentationIndex();
        $xmlElement .= $this->getCurrentIndentation();
        $xmlElement .= $this->getXmlElementCloseTag($inputArray);


        return $xmlElement;
    }

    /**
     * Return the XML element open tag of $arrayElement
     * @param Array $arrayElement
     *
     * @return string
     * @throws Exception
     */
    private function getXmlElementOpenTag($arrayElement)
    {
        if (is_null($arrayElement['name'])) {
            Throw new Exception('Malformed input array, name element is missing');
        }
        $attr = '';
        if (!empty($arrayElement['attr'])) {
            foreach ($arrayElement['attr'] as $attributeName => $attributeValue) {
                $attr .= ' ' . $attributeName . '="' . $attributeValue . '"';
            }
        }

        return self::OPEN_XML_TAG_START_CHAR . $arrayElement['name'] . $attr . self::OPEN_XML_TAG_END_CHAR;
    }

    /**
     * Return the XML element close tag of $arrayElement
     * @param Array $arrayElement
     *
     * @return string
     * @throws Exception
     */
    private function getXmlElementCloseTag($arrayElement)
    {
        if (is_null($arrayElement['name'])) {
            Throw new Exception('Malformed input array, name element is missing');
        }

        return self::CLOSE_XML_TAG_START_CHAR . $arrayElement['name'] . self::CLOSE_XML_TAG_END_CHAR;
    }

    private function increaseIndentationIndex()
    {
        self::$indentationDeep++;
    }


    private function decreaseIndentationIndex()
    {
        self::$indentationDeep--;
    }

    private function getCurrentIndentation()
    {
        if (self::$indentationDeep <= 0) {

            return "";
        }

        return str_repeat(self::INDENTATION_CHAR, self::INDENTATION_SIZE * self::$indentationDeep);
    }
}