<?php
// In dieser Datei werden die Events und deren Observer in einem Array definiert
$observers = array(
//	array('Event','Module'),
//	array('gotReleases','Music'),
	array('gotNewReleases','Music'),
	array('userLoggedIn','Session'),
	array('userLoggedOut','Session'),
//	array('willUpdateLang',''),
//	array('didUpdateLang',''),
//	array('willInit5',''),
//	array('didInit5',''),
//	array('willShutdown',''),
//	array('didShutdown',''),
//	array('Cundd_Core_cundd_managed_user_dir_created',''),
//	array('dirCreated',''),

	// Thread
//	array('willCreateThread',''),
//	array('didCreateThread',''),
);




?>