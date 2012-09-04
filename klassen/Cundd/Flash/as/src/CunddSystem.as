package
{
	import Cundd.Core.*;
	import Cundd.Core.controllers.*;
	import Cundd.Core.resources.MacMouseWheelHandler.MacMouseWheelHandler;
	import Cundd.Tools.*;
	
	import flash.display.MovieClip;
	import flash.events.Event;
	import flash.external.ExternalInterface;
	import flash.system.ApplicationDomain;
	
	import nl.demonsters.debugger.MonsterDebugger;
	
	
	/**
	 * 
	 * @author daniel
	 * 
	 */
	public class CunddSystem extends Cundd_Core_Object
	{
		/**
		 * Defines the kind of firebug-console-output is used by default
		 * String 'log'|'debug'|'info'|'warn'|'error' 
		 */
		protected static var _firebug_statusString:String = 'log';
		
		protected var _main:MovieClip;
		
		public static const version:String = '1.0.0';
		public static const flashPlayerVersion:String = '9.0';
		
		
		
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//
		// SYSTEM INITIALISATION
		//
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		public function CunddSystem(Main:MovieClip){
			this._init(Main);
		}
		
		
		public function _init(Main:MovieClip):void{
			this._main = Main;
			this.addEventListener(Event.ADDED_TO_STAGE, this._initMacMouseWheelHandler);
		}
		
		
		
		protected function _initMacMouseWheelHandler(event:Event):void{
			//MacMouseWheelHandler.init(this._main.stage);
		}
		
		
		
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//
		// CONTROLLERS
		//
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		
		
		/**
		 * Die Methode gibt eine Instanz des Controllers entsprechend dem übergebenen String 
		 * zurück.
		 * @param controller:String
		 * @return 
		 * 
		 */
		public static function getController(controller:String,arguments:Object = null):*{
			var controllerName:String = CunddSystem._getControllerPath(controller);
			CunddSystem.firebug(controllerName);
			
			
			//controllerName
			// var controllerObject:Core_AbstractController = eval('Cundd_Core_IndexController' + 'getInstance();');
			//controllerName.getInstance();
			//var controllerClassName:String = getQualifiedClassName(controllerName);
			var controllerObject:* = Cundd_Core_Loader.getInstance(controllerName, arguments);
			
			// Cundd.firebug(controllerClass);
			
			// controllerClass.getInstance();
			//var controllerObject:Core_AbstractController = new controllerName;
			
			
			return controllerObject;
		}
		
		
		
		/**
		 * Get the path and name for the given controller
		 * @param controller:String 'Module/Controller'
		 * @return String
		 * 
		 */
		protected static function _getControllerPath(controller:String):String{
			var relevantNamespace:String = 'Cundd';
			var newControllerPath:String = relevantNamespace + '.' +CunddSystem.getControllerModuleName(controller) + '.controllers.' + CunddSystem._getControllerName(controller);
			return newControllerPath;
		}
		
		
		
		/**
		 * Get the name for the given controller
		 * @param controller:String 'Module/Controller'
		 * @return String
		 * 
		 */
		protected static function _getControllerName(controller:String):String{
			var relevantNamespace:String = 'Cundd';
			var newControllerName:String = relevantNamespace + '_' + controller.replace('/','_') + 'Controller';
			return newControllerName;
		}
		
		
		
		/**
		 * Die Methode ermittelt den Modulnamen aus einem übergebenen Module/Controller-String
		 * @param controller Module/Controller
		 * @return 
		 * 
		 */
		public static function getControllerModuleName(controller:String):String{
			var tempArray:Array = controller.split('/',1);
			return tempArray[0];
		}
		
		
		
		
		 
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//
		// TOOLS/DEBUGGING
		//
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		
				
		/**
		 * The method sends a given message to the firebug-extension from firefox
		 * @param msg
		 * @return 
		 * 
		 */
		public static function throwFireBug(msg:Object,noTrace:Boolean = false):void{
			if(!noTrace){
				trace(msg);
			}
			
			var functionCall:String = 'console.' + CunddSystem._firebug_statusString;
		//	var returnValue:* = ExternalInterface.call(functionCall, msg);
			return;
		}
		public static function throwFirebug(msg:Object):void{
			return CunddSystem.throwFireBug(msg);
		}
		public static function firebug(msg:Object):void{
			return CunddSystem.throwFireBug(msg);
		}
		
		
		
		public static function date():String{
			return Cundd_Tools_Date.date;
		}
		public static function time():String{
			return Cundd_Tools_Date.time;
		}
		
	}
}