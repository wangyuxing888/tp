<?php

/**
 * [TQBlog] (C)2008-2028 tqtqtq.com
 * @author TQBlog Team 
 * This is NOT a freeware, use is subject to license terms
 * $Id: member.php 33828 2008-02-22 09:25:26Z team $
 */
class Member extends Base {

    private $_avatar = '';

    function __construct() {
        global $tqb;
        parent::__construct($tqb->table['Member'], $tqb->datainfo['Member']);

        $this->Name = $tqb->lang['msg']['anonymous'];
    }

    function __call($method, $args) {
        foreach ($GLOBALS['Filter_Plugin_Member_Call'] as $fpname => &$fpsignal) {
            $fpreturn = $fpname($this, $method, $args);
            if ($fpsignal == PLUGIN_EXITSIGNAL_RETURN) {
                return $fpreturn;
            }
        }
    }

    public function __set($name, $value) {
        global $tqb;
        if ($name == 'Url') {
            $u = new UrlRule($tqb->option['CFG_AUTHOR_REGEX']);
            $u->Rules['{%id%}'] = $this->ID;
            $u->Rules['{%alias%}'] = $this->Alias == '' ? urlencode($this->Name) : $this->Alias;
            return $u->Make();
        }
        if ($name == 'Avatar') {
            return null;
        }
        if ($name == 'LevelName') {
            return null;
        }
        if ($name == 'EmailMD5') {
            return null;
        }
        if ($name == 'Template') {
            if ($value == $tqb->option['CFG_INDEX_DEFAULT_TEMPLATE'])
                $value = '';
            return $this->data[$name] = $value;
        }
        parent::__set($name, $value);
    }

    public function __get($name) {
        global $tqb;
        if ($name == 'Url') {
            $u = new UrlRule($tqb->option['CFG_AUTHOR_REGEX']);
            $u->Rules['{%id%}'] = $this->ID;
            $u->Rules['{%alias%}'] = $this->Alias == '' ? urlencode($this->Name) : $this->Alias;
            return $u->Make();
        }
        if ($name == 'Avatar') {
            foreach ($GLOBALS['Filter_Plugin_Mebmer_Avatar'] as $fpname => &$fpsignal) {
                $fpreturn = $fpname($this);
                if ($fpreturn)
                    return $fpreturn;
            }
            if ($this->_avatar)
                return $this->_avatar;
            $s = $tqb->contentdir . 'avatar/' . $this->ID . '.png';
            if (file_exists($s)) {
                $this->_avatar = $tqb->host . 'content/avatar/' . $this->ID . '.png';
                return $this->_avatar;
            }
            $this->_avatar = $tqb->host . 'content/avatar/0.png';
            return $this->_avatar;
        }
        if ($name == 'LevelName') {
            return $tqb->lang['user_level_name'][$this->Level];
        }
        if ($name == 'EmailMD5') {
            return md5($this->Email);
        }
        if ($name == 'Meta') {
            return $this->Metas->serialize();
        }
        if ($name == 'Template') {
            $value = $this->data[$name];
            if ($value == '')
                $value = $tqb->option['CFG_INDEX_DEFAULT_TEMPLATE'];
            return $value;
        }
        return parent::__get($name);
    }

    static function GetPassWordByGuid($ps, $guid) {

        return md5(md5($ps) . $guid);
    }

    function Save() {
        global $tqb;
        if ($this->Template == $tqb->option['CFG_INDEX_DEFAULT_TEMPLATE'])
            $this->data['Template'] = '';
        foreach ($GLOBALS['Filter_Plugin_Member_Save'] as $fpname => &$fpsignal) {
            $fpreturn = $fpname($this);
            if ($fpsignal == PLUGIN_EXITSIGNAL_RETURN) {
                return $fpreturn;
            }
        }
        return parent::Save();
    }

}
