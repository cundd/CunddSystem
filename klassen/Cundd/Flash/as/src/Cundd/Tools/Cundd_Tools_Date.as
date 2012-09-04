package Cundd.Tools
{
	public class Cundd_Tools_Date extends Cundd_Tools_Abstract
	{
		// Format: 05:03:01
		// Format: 2010-01-05
		
		
		
		public function Cundd_Tools_Date(arguments:Array=null)
		{
			super(arguments);
		}
		
		
		
		public static function getDateObject():Date{
			return new Date();
		}
		
		
		
		/**
		 * Return the current time in format 19:55:47 GMT+0100
		 * @return 
		 * 
		 */
		public static function get time():String{
			return Cundd_Tools_Date.getDateObject().toTimeString();
		}
		
		
		
		/**
		 * Return the current date in format 19:55:47 GMT+0100
		 * @return 
		 * 
		 */
		public static function get date():String{
			return Cundd_Tools_Date.getDateObject().toDateString();
		}
		
	}
}