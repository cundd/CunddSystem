<?php

//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license 
 * @copyright 
 * Die Klasse CunddTerminal.
 * @package Cundd
 * @version 1.0
 * @since Nov 30, 2009
 * @author daniel
 */
class CunddTerminal {

    public function CunddTerminal() {
	$controllerAndAction = Cundd::getControllerAndAction('Terminal/Index');
	if($controllerAndAction){
	    include($controllerAndAction['absControllerClassPath']);
	    Cundd_Terminal_IndexController::indexAction();
	}
    }

}

?>