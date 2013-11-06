<?php

namespace okazo\toolsBundle\Services;

class tools {

    protected $locale;
    protected $isBot;

    public function __construct($locale) {
        $this->locale = $locale;
    }

    /**
     * Language Functions
     */
    function getDefaultLanguage() {
        if (isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]))
            return $this->parseDefaultLanguage($_SERVER["HTTP_ACCEPT_LANGUAGE"]);
        else
            return $this->parseDefaultLanguage(NULL);
    }

    private function parseDefaultLanguage($http_accept) {
        $deflang = $this->locale;
        if (!$deflang) {
            $deflang = "en";
        }

        if (isset($http_accept) && strlen($http_accept) > 1) {
            # Split possible languages into array
            $x = explode(",", $http_accept);
            foreach ($x as $val) {
                #check for q-value and create associative array. No q-value means 1 by rule
                if (preg_match("/(.*);q=([0-1]{0,1}\.\d{0,4})/i", $val, $matches))
                    $lang[$matches[1]] = (float) $matches[2];
                else
                    $lang[$val] = 1.0;
            }

            #return default language (highest q-value)
            $qval = 0.0;
            foreach ($lang as $key => $value) {
                if ($value > $qval) {
                    $qval = (float) $value;
                    $deflang = $key;
                }
            }

            //traite les cultures complètes (type en-US) pour en retirer juste la première partie (iso 639-1)
            if (strpos($deflang, "-") !== false) {
                $x = explode("-", $deflang);
                $deflang = $x[0];
            }

            if (!$deflang) {
                $deflang = "en";
            }
            if ($deflang === "") {
                $deflang = "en";
            }
        }
        return strtolower($deflang);
    }

    function isBot() {
        if ($this->isBot === \NULL) {
            if (preg_match('/(bot|spider|yahoo)/i', $_SERVER["HTTP_USER_AGENT"])) {
                $this->isBot = true;
            } else {
                $this->isBot = false;
            }
        }

        return $this->isBot;
    }

}
