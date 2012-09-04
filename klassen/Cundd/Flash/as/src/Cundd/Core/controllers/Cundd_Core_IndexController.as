package Cundd.Core.controllers
{
	import Cundd.Core.controllers.Cundd_Core_AbstractController;
	
	public class Cundd_Core_IndexController extends Cundd_Core_AbstractController
	{
		public function Cundd_Core_IndexController(arguments:* = null)
		{
			super(arguments);
		}
		
		
		
		public static function getInstance():Cundd_Core_IndexController{
			return new Cundd_Core_IndexController();
		}
	}
}