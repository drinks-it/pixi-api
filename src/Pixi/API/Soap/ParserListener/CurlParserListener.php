<?php

namespace Pixi\API\Soap\ParserListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CurlParserListener implements EventSubscriberInterface
{

    public $content = array();

    public $isRowContent = false;

    public $contentIndex = 0;

    public $currentTag = false;

    public $lastTag;

    /**
     * Mandatory method, which tells the dispatcher, which events
     * this subsriber is listening to and what method in the
     * subscriber will be called.
     * Additionally defines the priority
     * of the subscriber methods.
     *
     * @access public
     * @param void
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            "tag.open"  => array("onTagOpen", 0),
            "tag.data"  => array("onTagData", 0),
            "tag.close" => array("onTagClose", 0),
        );
    }

    /**
     * This method adds some additional information
     * to the data and creates a string, which will be
     * written to the file.
     *
     * @access public
     * @param array $event
     * @return void
     */
    public function onTagOpen($event)
    {
        if ($event['tagName'] == 'row') {
            $this->isRowContent = true;
            $this->content[$this->contentIndex] = array();
        }
    }

    /**
     * Is executed when the data of an xml tag is parsed.
     * It just trims the data, without further modification.
     *
     * @access public
     * @param array $event
     * @return void
     */
    public function onTagData($event)
    {

        if ($this->isRowContent AND $event['tagName'] != 'row') {
            if (isset($this->content[$this->contentIndex][$event['tagName']])) {
                $this->content[$this->contentIndex][$event['tagName']] .= $event['data'];
            } else {
                $this->content[$this->contentIndex][$event['tagName']] = $event['data'];
            }
        }

    }

    /**
     * This method adds some additional information
     * to the data and creates a string, which will be
     * written to the file.
     *
     * @access public
     * @param array $event
     * @return void
     */
    public function onTagClose($event)
    {
        if ($event['tagName'] == 'row') {
            $this->isRowContent = false;
            $this->contentIndex++;
        }
    }

    public function getResultSet()
    {
        return $this->content;
    }

}
