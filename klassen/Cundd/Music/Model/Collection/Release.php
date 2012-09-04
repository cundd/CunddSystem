<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license
 * @copyright
 * Die Klasse Cundd_Music_Model_Collection_Release erweitert Cundd_Music_Model_Collection_Abstract.
 * @package Cundd_Music
 * @version 1.0
 * @since Dec 14, 2009
 * @author daniel
 */
class Cundd_Music_Model_Collection_Release extends Cundd_Music_Model_Collection_Abstract{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode überschreibt die Methode der Superclass und feuert einen Event. */
	protected function _getCol($arguments){
		$result = parent::_getCol($arguments);

		// Fire the Event
		if($result AND $this->_fetchFromMusicbrainz) new CunddEvent('gotNewReleases',$result);

		return $result;
	}
}
?>