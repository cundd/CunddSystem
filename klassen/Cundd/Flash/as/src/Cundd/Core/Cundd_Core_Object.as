package Cundd.Core
{
	import flash.display.MovieClip;
	import flash.events.*;
	import flash.utils.*;
	
	public class Cundd_Core_Object extends MovieClip // Object
	{
		/**
		 * 'strict'|'transitional' Defines if the error()-method-call throws an error.
		 */
		public var errorHandling:String = 'strict';
		
		public var isPlaying:Boolean = false;
		
		protected var _cundd_description_descriptionList:Array;
		
		internal var data:*
		
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//
		// INIT
		//
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		public function Cundd_Core_Object(arguments:Array = null)
		{
			super();
			this._registerProperties(arguments);
		}
		
		
		
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//
		// MAIN
		//
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		protected function _registerProperties(source:Array,prepareSourceKeys:Boolean = false,prefix:String = ''):void{
			for(var key:String in source){
				this._setIfKeyExists(key,source);
			}
		}
		
		
		
		/**
		 * Die Methode setzt alle öffentlichen Variablen deren Name einem Array-Key entspricht.
		 * @param key
		 * @param source
		 * @param target
		 * @param elseTo
		 * 
		 */
		protected function _setIfKeyExists(key:String,source:Array,target:Object = '_cundd_noTargetHasBeenSet',elseTo:Object = '_cundd_noElseValueHasBeenSet'):void{
			var allProperties:Array = this._getVariablesAsArray();
			
			if(target !== '_cundd_noTargetHasBeenSet'){ // Überprüfen ob ein Target angegeben wurde
				if(source[key] != undefined){
					target = source[key];
				} else if(elseTo == '_cundd_noElseValueHasBeenSet'){
					target = elseTo;
				}
			} else if(allProperties.indexOf(key) != -1){ // Überprüfen ob eine Property mit dem Key existiert (wenn nicht wird -1 zurückgegeben)
				if(source[key] != undefined){
					this[key] = source[key];
				} else if(elseTo == '_cundd_noElseValueHasBeenSet'){
					this[key] = elseTo;
				}
			}
		}
		
		
		
		/**
		 * Die Methode ermittelt die Properties des Objekts und sortiert sie in einem Array. 
		 * @return 
		 * 
		 */
		protected function _getPropertiesAsArray():Array{
			var tempResult:Array = this._getDescriptionAsArray();
			if(tempResult['property'] === undefined){
				return new Array();
			} else {
				return tempResult['property'];
			}
		}
		
		
		
		/**
		 * Die Methode ermittelt die Variables des Objekts und sortiert sie in einem Array. 
		 * @return 
		 * 
		 */
		protected function _getVariablesAsArray():Array{
			var tempResult:Array = this._getDescriptionAsArray();
			if(tempResult['variable'] === undefined){
				return new Array();
			} else {
				return tempResult['variable'];
			}
		}
		
		
		
		/**
		 * Die Methode ermittelt die Methoden des Objekts und sortiert sie in einem Array. 
		 * @return 
		 * 
		 */
		protected function _getMethodsAsArray():Array{
			var tempResult:Array = this._getDescriptionAsArray();
			if(tempResult['method'] === undefined){
				return new Array();
			} else {
				return tempResult['method'];
			}
		}
		
		
		
		/**
		 * Die Methode ermittelt die Description des Objekts und sortiert sie in einem Array.
		 * @return 
		 * 
		 */
		protected function _getDescriptionAsArray():Array{
			var resultArray:Array = new Array();
			var kind:String;
			var name:String;
			var type:String;
			
			var tempXml:XML = describeType(this);
			
			// Wenn die Methode schon einmal aufgerufen wurde wird das gespeicherte Ergebnis zurückgegeben
			if(this._cundd_description_descriptionList){
				return this._cundd_description_descriptionList;
			}
			
			for each (var node:XML in tempXml.children()) {
				kind = node.name();
				name = node.attribute('name');
				if(kind == 'variable'){	type = node.attribute('type');	}
				// if(kind == 'accessor'){ type = node.attribute('type'); kind = 'variable'; }
				
				// Im resultArray-Speichern
				if(resultArray[kind] === undefined){
					resultArray[kind] = new Array();
				}
				
				try{
				var oldKindArray:Array = resultArray[kind]// as Array;
				oldKindArray.push(name);
				} catch(e:Error){}
				
				resultArray[kind] = oldKindArray;
				
				// Wenn es variable oder accessor ist
				if(kind == 'variable' || kind == 'accessor'){
					kind = 'property';
					type = node.attribute('type');
					
					// Im resultArray-Speichern
					if(resultArray[kind] === undefined){
						resultArray[kind] = new Array();
					}
					
					try{
						oldKindArray = null;
						oldKindArray = resultArray[kind]// as Array;
						oldKindArray.push(name);
					} catch(e:Error){}
					
					resultArray[kind] = oldKindArray;
				}
			}
			
			this._cundd_description_descriptionList = resultArray;
			return resultArray;
		}
		
		
		
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//
		// DEBUG
		//
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		/**
		 * The method sends a given message to the firebug-extension from firefox
		 * @param msg
		 * @return 
		 * 
		 */
		public function throwFireBug(msg:String = null):void{
			if(!msg){
				//msg = this.toString();
			}
			
			return CunddSystem.firebug(msg);
		}
		public function throwFirebug(msg:String = null):void{
			return this.throwFireBug(msg);
		}
		public function firebug(msg:String = null):void{
			return this.throwFireBug(msg);
		}
		public function fb(msg:String = null):void{
			return this.throwFireBug(msg);
		}
		public function echo(msg:String = null):void{
			return this.throwFirebug(msg);
		}
		
		
		
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//
		// EVENTS
		//
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		/**
		 * Die Methode feuert einen neuen Event.
		 * @param type 
		 * 
		 */
		public function event(type:String):void{
			var event:Event = new Event(type);
			dispatchEvent(event);
		}
		
		
		
		/**
		 * Die Methode setzt einen EventListener für alle Kind-Elemente.
		 * @param type
		 * @param listener
		 * @param useCapture
		 * @param priority
		 * @param useWeakReference
		 * 
		 */
		public function addEventListeners(type:String,listener:Function,useCapture:Boolean = false,priority:int = 0,useWeakReference:Boolean = false):void{
			for(var i:uint; i < this.numChildren;i++){
				echo('g'+this.getChildAt(i))
				this.getChildAt(i).addEventListener(type,listener,useCapture,priority,useWeakReference);
			}
		}
		
		
		
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//
		// STRINGS
		//
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		/**
		 * Die Methode ermittelt mittels einer regular-expression.
		 * @param url 
		 * 
		 */
		protected function _getDomainFromUrl(url:String):String{
			var pattern:RegExp = /http?:\/\/+[a-zA-Z0-9.-]*/g;
			var securityDomainArray:Array = url.match(pattern);
			return securityDomainArray[0];
		}
		
		
		
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//
		// ERROR
		//
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		/**
		 * Die Methode bewirkt das System-interne ErrorHandling.
		 * @param msg 
		 * 
		 */
		public function error(msg:String):void{
			if(this.errorHandling == 'strict'){
				throw new Error(msg);
			} else {
				this.firebug(msg);
			}
		}
		
		
		
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//
		// OVERRIDING PLAY AND STOP
		//
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		/**
		 * Override the original stop-method to integrate change of the isPlaying-property.
		 * 
		 */
		override public function stop():void{
			this.isPlaying = false;
			super.stop();
		}
		
		
		
		/**
		 * Override the original play-method to integrate change of the isPlaying-property. 
		 * 
		 */
		override public function play():void{
			this.isPlaying = true;
			super.play();
		}
		
		
		/**
		 * Override the original gotoAndPlay-method to integrate change of the isPlaying-property.
		 * @param frame
		 * @param scene
		 * 
		 */
		override public function gotoAndPlay(frame:Object,scene:String = null):void{
			this.isPlaying = true;
			super.gotoAndPlay(frame,scene);
		}
		
		
		/**
		 * Override the original gotoAndStop-method to integrate change of the isPlaying-property. 
		 * @param frame
		 * @param scene
		 * 
		 */
		override public function gotoAndStop(frame:Object,scene:String = null):void{
			this.isPlaying = false;
			super.gotoAndStop(frame,scene);
		}
		
		
	}
}