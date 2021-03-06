<?php

/**
 * File: Display.php
 * Encoding: UTF-8
 * @package: MOODLE PLUGINS
 * @subpackage brigo
 * @copyright  Luuk Verhoeven [MoodleFreak.com]
 * */
class Brigo_Display
{

    protected $pageList = array('friends' => array('allowGuest' => true), 'publicRoom' => array('allowGuest' => true), 'dashboard' => array('allowGuest' => true));
    protected $content = '';
    protected $errors = array();
    protected $page = '';
    public $javascripts = array();

    public function __construct($page = '')
    {
        if (!isset($this->pageList[$page]))
        {
            $page = 'dashboard';
        }

        global $CFG;
        $this->addJavascript($CFG->wwwroot . '/blocks/brigo/js/brigo.js');

        //is callable page
        $this->$page();

        $this->page = $page;
    }

    /**
     * return friends overview
     */
    public function friends()
    {
        global $CFG;
        $this->addJavascript($CFG->wwwroot . '/blocks/brigo/js/jquery.tinyscrollbar.min.js');

        $this->buffer('start');
        include dirname(__FILE__) . '/../view/page/friends.php';
        $this->buffer('end');
    }

    /**
     * add a javascript to js holder
     * @param string $src
     */
    public function addJavascript($src = '')
    {
        $this->javascripts[] = (string) $src;
        return $this->javascripts;
    }

        /**
     * dashboard
     */
    public function dashboard()
    {
        global $CFG;
        //$this->addJavascript($CFG->wwwroot . '/blocks/brigo/js/jquery.tinyscrollbar.min.js');
        //$this->addJavascript($CFG->wwwroot . '/blocks/brigo/js/brigo_publicroom.js');
        $this->buffer('start');
        include dirname(__FILE__) . '/../view/page/dashboard.php';
        $this->buffer('end');
    }


    /**
     * publicRoom
     */
    public function publicRoom()
    {
        global $CFG;
        $this->addJavascript($CFG->wwwroot . '/blocks/brigo/js/jquery.tinyscrollbar.min.js');
        $this->addJavascript($CFG->wwwroot . '/blocks/brigo/js/brigo_publicroom.js');

        $this->buffer('start');
        include dirname(__FILE__) . '/../view/page/room.php';
        $this->buffer('end');
    }

    /**
     * getContent
     * @return string
     */
    public function getContent()
    {
        if (!empty($this->errors))
        {
            $this->content = '<div id="brigoErrors">';
            foreach ($this->errors as $message)
            {
                $this->content .= '<div class="message">' . $message . '</div>';
            }
            $this->content .= '</div>';
        }

        return $this->content;
    }

    /**
     * do content buffering
     * @param type $action
     */
    private function buffer($action = 'start')
    {
        if ($action == 'start')
        {
            ob_start();
        }
        else
        {
            $this->content .= ob_get_contents();
            ob_end_clean();
        }
    }

    /**
     * set a error message
     * @param string $message
     */
    private function setError($message = '')
    {
        $this->errors[] = (string) $message;
        return $this->errors;
    }

    /**
     * check if page is selected
     * @param string $page
     * @return string
     */
    public function isActivePage($page = '')
    {
        if ($this->page == $page)
        {
            return 'active';
        }
        return '';
    }

}