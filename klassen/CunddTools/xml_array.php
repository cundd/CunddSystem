<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Die Funktion xml_array() liest die Daten eines xml-dom-Objekts in ein Array und gibt 
 dieses zurück. */
/* MANY THANKS TO lociii http://www.php-resource.de/forum/xml/46707-mit-php-xml-auslesen-item-prob.html#post309234 */
function xml_array($node)
{
	$xml_arr = array();
	while ($node)
	{
		if ($node->has_child_nodes())
		{
			$first_child = $node->first_child();
			if ($first_child->node_type() == XML_ELEMENT_NODE) 
			{
				$xml_arr[$node->node_name()][] = xml_array($first_child);
			}
			else
			{
				if (isset($xml_arr[$node->node_name()]))
				{
					if (!is_array($xml_arr[$node->node_name()]))
						$xml_arr[$node->node_name()] = array($xml_arr[$node->node_name()]);
					
					$xml_arr[$node->node_name()][] = stripslashes(utf8_decode($first_child->node_value()));
				}
				else
				{
					$xml_arr[$node->node_name()] = stripslashes(utf8_decode($first_child->node_value()));
				}
			}
		}
		$node = $node->next_sibling();
	}
	return $xml_arr;
} // end xml_array();
	
?>