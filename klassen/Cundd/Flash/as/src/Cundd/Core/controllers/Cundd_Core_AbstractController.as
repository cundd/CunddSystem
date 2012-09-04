package Cundd.Core.controllers
{
	import Cundd.Core.Cundd_Core_Loader;
	import Cundd.Core.Cundd_Core_Object;
	import Cundd.Core.Cundd_Event;
	
	import flash.display.DisplayObject;
	import flash.display.MovieClip;
	import flash.display.Sprite;
	import flash.events.*;
	import flash.geom.ColorTransform;
	import flash.net.URLLoader;
	import flash.net.URLLoaderDataFormat;
	import flash.net.URLRequest;
	import flash.utils.getQualifiedClassName;
	
	public class Cundd_Core_AbstractController extends Cundd_Core_Object
	{
		protected var _arguments:Object;
		protected var _response:*;
		protected var _responseXml:XML;
		protected var _dynamicFunctions:Array = new Array();
		protected var _loader:URLLoader;
		
		public var request:URLRequest;
		public var objectCollection:Array = new Array();
		public var setup:XMLList;
		
		
		
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		// EVENTS
		public static const WILL_SEND_REQUEST:String 	= 'will_send_request';
		public static const DID_SEND_REQUEST:String 	= 'did_send_request';
		
		public static const WILL_CREATE_OBJECTS:String 	= 'will_create_objects';
		public static const DID_CREATE_OBJECTS:String 	= 'did_create_objects';
		
		public static const WILL_CREATE_SINGLE_OBJECT:String 	= 'will_create_single_object';
		public static const DID_CREATE_SINGLE_OBJECT:String 	= 'did_create_single_object';
		
		
		
		public static const HANDLE_DATA:String 			= 'handle_data';
		public static const COMPLETE:String 			= 'complete';
		
		
		
		public function Cundd_Core_AbstractController(arguments:Object = null):void
		{
			this._init(arguments);
		}
		
		
		
		protected function _init(arguments:* = null):Boolean{
			var say:Boolean = true;
			if(arguments){
				// Das übergebene Argument verarbeiten
				this._arguments = arguments;
				
				var className:String = getQualifiedClassName(this._arguments);
				switch(className){
					case 'flash.net::URLRequest':
						this.request = arguments;
						break;
					
					case 'Array':
						if(this._arguments['request']){
							this.request = this._arguments['request'];
						}
						break;
						
					case 'String':
						this.request = new URLRequest(arguments);
						break;
					
					default:
						CunddSystem.firebug(className);
						break;
				}
				
				if(say){CunddSystem.firebug(className);}
			}
			
			if(this.request){
				this.sendRequest();
			}
			
			return true;
		}
		
		
		
		/**
		 * Die Methode sendet den ursprünglichen Request erneut, wenn keine Parameter 
		 * übergeben wurden, sonst initialisiert die Methode dieses Objekt mit den 
		 * übergebenen Parametern neu.
		 * @param arguments
		 * @return 
		 * 
		 */
		public function refresh(arguments:* = null):Boolean{
			this.cancel();
			this.clear();
			if(arguments){ // New request
				return this._init(arguments);
			} else { // Resend old request
				this.sendRequest();
				return true;
			}
		}
		
		
		
		/**
		 * Die Methode löscht alle Elemente.
		 * @return 
		 * 
		 */
		public function clear():Boolean{
			while(this.numChildren > 0){
				this.removeChildAt(0);
			}
			if(this.numChildren == 0){
				return true;
			} else {
				return false;
			}
		}
		
		
		
		/**
		 * Die Methode bricht den Ladevorgang ab.
		 * 
		 */
		public function cancel():void{
			if(this._loader){
				this._loader.close();
			} else {
				echo('no _loader');
			}
			return;
		}
		
		
		
		/**
		 * Die Methode sendet den Request und installiert einen EventListener.
		 * 
		 */
		public function sendRequest():void{
			if(!this.request){
				throw new Error("No request set");
				return;
			}
			
			this.event(Cundd_Event.WILL_SEND_REQUEST);
			this._loader = new URLLoader();
			this._loader.load(this.request);
			this._loader.dataFormat = URLLoaderDataFormat.TEXT;
			
			// Nach dem vollständigen Laden die Methode "_parseData" ausführen
			this._loader.addEventListener(Event.COMPLETE, this._parseData);
			
			
			/*var php_anfrage:URLRequest = new URLRequest();
			php_anfrage.data = php_parameter;
			php_anfrage.url = CunddConfig.phpServer;
			php_anfrage.method = URLRequestMethod.POST;
			
			// URLLoader instanzieren
			var loader:URLLoader = new URLLoader();
			loader.load(php_anfrage);
			loader.dataFormat = URLLoaderDataFormat.TEXT;
			
			// Nach dem vollständigen Laden die Methode "parse_xml" ausführen
			loader.addEventListener(Event.COMPLETE, this.parse_xml);
			*/
		}
		
		
		
		/**
		 * Die Methode parsed die Daten
		 * @param loaderEvent
		 * @return 
		 * 
		 */
		protected function _parseData(loaderEvent:Event):Boolean{
			this.event(Cundd_Event.DID_SEND_REQUEST);
			
			this._response = loaderEvent.target.data;
			this._responseXml = new XML(this._response);
			this._responseXml.ignoreWhitespace = true;
			
			
			if(this._responseXml is XML){
				return this._handleResponse();
			} else {
				return false;
			}
		}
		
		
		
		/**
		 * Die Methode bietet Schnittstellen zum Eingriff vor und nach dem Erstellen der 
		 * Objekte.
		 * @return 
		 * 
		 */
		protected function _handleResponse():Boolean{
			var result:Boolean = false;
			var dontBreakCreation:Boolean = true;
			
			dontBreakCreation = this._beforeCreateObjects();
			
			if(dontBreakCreation){
				result = this.createObjects();
				this._afterCreateObjects();
			}
			
			return result;
		}
		
		
		
		/**
		 * Die Methode wird vor dem Erstellen der Objekte ausgeführt.
		 * @return 
		 * 
		 */
		protected function _beforeCreateObjects():Boolean{
			return true;
		}
		
		/**
		 * Die Methode wird nach dem Erstellen der Objekte ausgeführt.
		 * 
		 */
		protected function _afterCreateObjects():void{
		}
		
		
		
		/**
		 * Die Methode erstellt die Objekte.
		 * 
		 */
		public function createObjects():Boolean{
			this.event(Cundd_Event.WILL_CREATE_OBJECTS);
			
			var objects:XMLList = this._responseXml.objects;
			
			CunddSystem.firebug(getQualifiedClassName(this._responseXml.objects));
			
			for each (var object:XML in objects.object) {
				this.createSingleObject(object);
			}
			/* */
			
			this.fb(this._responseXml.setup.statusString)
			this.setup = this._responseXml.setup;
			
			
			this.event(Cundd_Event.DID_CREATE_OBJECTS);
			
			this.event(Cundd_Event.COMPLETE);
			
			return true;
		}
		
		
		
		
		/**
		 * 
		 * @param object
		 * @return 
		 * 
		 */
		 public function createSingleObject(object:XML):Object{
			this.event(Cundd_Event.WILL_CREATE_SINGLE_OBJECT);
			var pos:int = this.objectCollection.length;
			
			this.objectCollection[pos] = Cundd_Core_Loader.getInstance(object.type,object,true);
			var tempObject:* = this.objectCollection[pos];
			
			if(tempObject is DisplayObject){
				tempObject.x = object.x;
				this.fb('object.x='+object.x);
				tempObject.y = object.y;
				this.fb('object.y='+object.y);
				
				if(object.hasOwnProperty('width') && object.width != '' &&
					object.width != null){
					this.fb('object.width='+object.width);
				}
				if(object.hasOwnProperty('height') && object.height != '' &&
					object.height != null){
					this.fb('object.height='+object.height);
				}
				if(object.hasOwnProperty('rotation') && object.rotation != '' &&
					object.rotation != null){
					this.fb('object.rotation='+object.rotation);
					tempObject.rotation = object.rotation;
				}
				this._setColorAndAlpha(tempObject,object);
				tempObject.visible = object.visible;
				
				// Add all data
				for each (var value:XML in object.data.children()) {
					var dataName:String = value.name();
					this._handleData(dataName,value);
				}
				
				if(tempObject is MovieClip || tempObject is Sprite || tempObject is DisplayObject){
					this.addChild(tempObject);
				}
			}
			
			if(tempObject){
				this.event(Cundd_Event.DID_CREATE_SINGLE_OBJECT);
				
				return tempObject;
			} else {
				return null;
			}
		}
		
		
		
		/**
		 * Die Methode setzt Farbe und Alpha des Objekts.
		 * @param targetObject
		 * @param sourceObject
		 * @return 
		 * 
		 */
		protected function _setEventListeners(targetObject:DisplayObject,sourceObject:XML):void{
			return;
			if(this._hasAndIsNotEmptyButMaybeZero(sourceObject,'eventlistener')){
				for each(var eventListener in sourceObject.eventlistener){
					CunddSystem.firebug('_setEventListeners');
					CunddSystem.firebug('Event='+eventListener.name.toString() +' Function='+eventListener.child('function').toString());
					var elName:String = eventListener.name.toString();
					var elFunction:String = eventListener.child('function').toString();
					elFunction = 'don';
					//var functionElFunction:Object = getDefinitionByName(elFunction);
					//var ff:Function = new Function();
					this._dynamicFunctions.push(elFunction);
					var pos:uint = this._dynamicFunctions.length() - 1;
					
					
					targetObject.addEventListener(elName,this.don)//this[_dynamicFunctions][pos].call());
				}
			}
			return;
		}
		
		
		public function don(e:Event):void{
			CunddSystem.firebug(e.target.name);
		}
		
		
		
		/**
		 * Die Methode setzt Farbe und Alpha des Objekts.
		 * @param targetObject
		 * @param sourceObject
		 * @return 
		 * 
		 */
		protected function _setColorAndAlpha(targetObject:DisplayObject,sourceObject:XML):void{
			this._setColor(targetObject,sourceObject);
			this._setAlpha(targetObject,sourceObject);
			return;
		}
		
		
		
		/**
		 * Die Methode setzt den Alpha-Wert des Objekts.
		 * @param targetObject
		 * @param sourceObject
		 * 
		 */
		protected function _setAlpha(targetObject:DisplayObject,sourceObject:XML):void{
			if(sourceObject.hasOwnProperty('alpha') && sourceObject.alpha != '' &&
					sourceObject.alpha != null){
					targetObject.alpha = sourceObject.alpha;
			}
			return;
		}
		
		
		
		/**
		 * Die Methode setzt die Farbe des Objekts.
		 * @param targetObject
		 * @param sourceObject
		 * 
		 */
		protected function _setColor(targetObject:DisplayObject,sourceObject:XML):void{
			if(sourceObject.hasOwnProperty('color') && sourceObject.color != '' &&
					sourceObject.color != null){
				// Den color-String passend vorbereiten (0xRRGGBB)
				var color:String = sourceObject.color;
				var colorPrepared:String;
				var r:String;
				var g:String;
				var b:String;
				
				if(color.length == 6){ // RRGGBB
					colorPrepared = '0x' + color;
				} else if(color.length == 8){ // 0xRRGGBB
					colorPrepared = color;
				} else if(color.length == 3){ // RGB
					r = color.charAt(0);
					g = color.charAt(1);
					b = color.charAt(2);
					colorPrepared = '0x' + r + r + g + g + b + b;
				} else if(color.length == 5){ // 0xRGB
					r = color.charAt(2);
					g = color.charAt(3);
					b = color.charAt(4);
					colorPrepared = '0x' + r + r + g + g + b + b;
				} else {
					// Wrong color given use white
					colorPrepared = '0xFFFFFF';
				}
				
				var colorTransformObj:ColorTransform = new ColorTransform();
				colorTransformObj.color = uint(colorPrepared.toString());
				targetObject.transform.colorTransform = colorTransformObj;
			}
			return;
		}
		
		
		
		/**
		 * Die Methode gibt das Ergebnis des URLRequest zurück. 
		 * @return 
		 * 
		 */
		public function getReponse():String{
			return this._response;
		}
		
		
		
		/**
		 * Die Methode gibt das parsed Ergebnis des URLRequest zurück. 
		 * @return 
		 * 
		 */
		public function getReponseXml():XML{
			return this._responseXml;
		}
		
		
		
		/**
		 * Die Methode wird zum Verarbeiten eines ermittelten Data Key/Value-Paars aufgerufen
		 * @param name
		 * @param value
		 * @return 
		 * 
		 */
		protected function _handleData(name:String,value:Object):void{
			this.event(Cundd_Event.HANDLE_DATA);
		}
		
		
		
		/**
		 * Überprüft ob das Objekt (sourceObject) eine Eigenschaft (has) hat und der 
		 * Wert dieser nicht '' oder null ist. 
		 * @param sourceObject
		 * @param has
		 * @return 
		 * 
		 */
		protected function _hasAndIsNotEmptyButMaybeZero(sourceObject:XML,has:String):Boolean{
			if(!sourceObject.hasOwnProperty(has)){
				return false;
			}
			
			if(sourceObject.child(has) != '' &&
					sourceObject.child(has) != null){
						return true;
			} else {
				return false;
			}
		}
		
		
		
		/**
		 * Die Methode gibt ein Objekt der eigenen Klasse zurück.
		 * @return 
		 * 
		 */
		public static function getInstance():Cundd_Core_AbstractController{
			/*
			 * return new Core_AbstractController
			 */
			throw new Error('Please define the factory method for this class!');
		}
		

	}
}