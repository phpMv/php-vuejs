<?php
namespace PHPMV;

use PHPMV\js\JavascriptUtils;

class VueManager{

    private static ?VueManager $instance = null;
    protected array $imports;
    protected array $vues;

    protected function __construct() {
        $this->imports = [];
        $this->vues = [];
    }

    public static function getInstance():VueManager {
        if (!isset(self::$instance)) {
            self::$instance = new VueManager();
        }
        return self::$instance;
    }

    public function addImport($import):void {
        $this->imports[] = $import;
    }

    public function addVue(VueJS $vue):void {
        $this->vues[] = $vue;
    }

    public function __toString():string {
        $script = implode("\n",$this->imports);
        $script .= implode("\n",$this->vues);
        return JavascriptUtils::wrapScript($script);
    }
}