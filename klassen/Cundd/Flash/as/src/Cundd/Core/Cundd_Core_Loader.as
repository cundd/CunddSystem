package Cundd.Core
{
	import flash.utils.Dictionary;
	import flash.utils.getDefinitionByName;
	
	public class Cundd_Core_Loader extends Cundd_Core_Object
	{
		public static var baseProperties:Array = ['type','x','y','width','height','rotation','alpha','name','id'];
		/**
		 * Die Methode gibt eine Instanz des der übergebenen Klasse zurück.
		 * @param classpath 'Cundd.Core.Cundd_Core_Object'
		 * @param arguments[optional]
		 * @return 
		 * 
		 */
		public static function getInstance(classpath:String, arguments:* = null, prepareArguments:Boolean = false):Object{
			var classObject:Class;
			var instance:Object = null;
			
			
			if(prepareArguments){
				arguments = Cundd_Core_Loader._prepareArguments(arguments);
			}
			
			try{
				classObject = getDefinitionByName(classpath) as Class;
				if(arguments){
					CunddSystem.firebug(arguments);
					instance = new classObject(arguments);
				} else {
					instance = new classObject();
				}
			} catch(e:Error) {
				if(e.errorID == 1063){ // Der Konstruktor hat keine Parameter erwartet
					// Versuche es erneut ohne Parameter
					instance = new classObject();
				} else {
					CunddSystem.firebug(e.name + ' ' + e.errorID + ' ' + e.message + e.getStackTrace());
				}
			}
			return instance;
		}
		
		
		
		/**
		 * Die Methode überprüft welcher Klasse das Argument angehört und ruft die jeweilige 
		 * Methode zum parsen in ein assoziatives Array auf.
		 * @param arguments
		 * @return 
		 * 
		 */
		protected static function _prepareArguments(arguments:*):Array{
			var returnVal:Array;
			if(arguments is Array){
				// CunddSystem.firebug('arg = array')
				returnVal = arguments;
			}
			if(arguments is XML){
				// CunddSystem.firebug('arg = xml')
				returnVal = Cundd_Core_Loader._prepareXml(arguments);
			}
			if(arguments is String){
				// CunddSystem.firebug('arg = string')
				returnVal = Cundd_Core_Loader._prepareString(arguments);
			}
			
			return returnVal;
		}
		
		
		
		/**
		 * Die Methode gibt ein assoziatives Array mit verschiedenen Keys und jeweils dem selben 
		 * Wert von arguments zurück.
		 * @param arguments
		 * @return 
		 * 
		 */
		protected static function _prepareString(arguments:String):Array{
			var returnVal:Array = new Array();
			returnVal['arguments'] = arguments;
			returnVal['argument'] = arguments;
			returnVal['data'] = arguments;
			
			return returnVal;
		}
		
		
		
		/**
		 * Die Methode durchläuft ein XML-Objekt und speichert die Daten in einem Array mit dem Key/value-Paar 
		 * 'nodeName' => 'nodeValue'. Darüberhinaus wird das data-XML-Objekt speziell behandelt und mit dem Key 'data'
		 * zurückgegeben.
		 * @param arguments
		 * @return 
		 * 
		 */
		protected static function _prepareXml(arguments:XML):*{
			var returnVal:Array = new Array();
			var data:Array = new Array();
			
			for each(var node:XML in arguments.children()){
				returnVal[String(node.name())] = String(node);
			}
			for each(var node2:XML in arguments.data.children()){
				var name:String = node2.name();
				var value:String = node2;
				
				data[name] = value;
			}
			
			returnVal['data'] = data;
			return returnVal;
		}
	}
}