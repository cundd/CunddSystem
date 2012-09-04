package Cundd.GraphicObject.UrlLoading
{
	import Cundd.GraphicObject.*;
	
	import flash.display.DisplayObject;
	import flash.display.Loader;
	import flash.events.*;
	import flash.net.*;
	import flash.system.LoaderContext;
	import flash.system.Security;
	
	internal class UrlLoadingAbstract extends Cundd.GraphicObject.Abstract
	{
		public var data:Array = new Array();
		public var arguments:Array = new Array();
		public var result:DisplayObject;
		
		
		protected var _urlRequest:URLRequest;
		protected var _noAutoload:Boolean = false;
		
		
		private var _url:String;
		
		
		
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//
		// INIT
		//
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		public function UrlLoadingAbstract(arguments:Array)
		{
			super(arguments);
			this._init(arguments);
		}
		
		
		
		protected function _init(arguments:Array):void{
			if(arguments.hasOwnProperty('data')){
				this._registerProperties(arguments['data']);
			}
			
			this.arguments = arguments;
			this._setIfKeyExists('data',arguments);
			this._setIfKeyExists('url',this.data,this.url);
			
			this._load();
			
			return;
		}
		
		
		
		protected function _load():void{// Loader
			if(!this._noAutoload && this.url){
				var tempLoader:Loader = new Loader();
				
				this._urlRequest = new URLRequest(this.url);
				// var securityDomain:String = this.url.replace('!/+[a-zA-Z0-9]!','');
				
				Security.allowDomain(this._getDomainFromUrl(this.url));
				Security.allowInsecureDomain(this._getDomainFromUrl(this.url));
				
				var loaderContext:LoaderContext = new LoaderContext(false);
				tempLoader.load(this._urlRequest,loaderContext);
				
				// EventListener fragt das Ende des Ladevorgangs oder das Auftauchen eine Fehlers ab 
				tempLoader.contentLoaderInfo.addEventListener(Event.COMPLETE, _handleComplete);
				tempLoader.contentLoaderInfo.addEventListener(IOErrorEvent.DISK_ERROR, _handleError);
				tempLoader.contentLoaderInfo.addEventListener(IOErrorEvent.IO_ERROR, _handleError);
				tempLoader.contentLoaderInfo.addEventListener(IOErrorEvent.NETWORK_ERROR, _handleError);
				tempLoader.contentLoaderInfo.addEventListener(IOErrorEvent.VERIFY_ERROR, _handleError);
			} else {
				// Nothing -> wait for further instructions
				this.fb('No autoload possible');
			}
		}
		
		
		
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//
		// URL ACCESS
		//
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		/**
		 * Die Methode ermittelt die gespeicherte Url. Wenn die Eigenschaft _url nicht gesetzt ist wird veruscht der passende Wert aus 
		 * data zu lesen.
		 * @return 
		 * 
		 */
		public function get url():String{
			var returnVal:String;
			if(this._url){
				returnVal = this._url;
			} else if(this.data['url'] != undefined){
				returnVal = this.data['url'];
				this._url = this.data['url'];
			}
			// CunddSystem.firebug('query url:'+returnVal+this.data['url'])
			return returnVal;
		}
		
		
		
		/**
		 * Die Methode erlaubt das setzen der Url.
		 * @param value
		 * 
		 */
		public function set url(value:String):void{
			this._url = value;
			return;
		}
		
		
		
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//
		// EVENT HANDLERS
		//
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		protected function _handleComplete(event:Event):void{
			this.result = event.target.content;
			if(this.arguments['width'] != undefined){
				this.result.width = this.arguments['width'];
			}
			if(this.arguments['height'] != undefined){
				this.result.height = this.arguments['height'];
			}
			
			this.addChild(this.result);
			
			this.event(Event.COMPLETE);
			
			return;
		}
		protected function _handleComplete2(event:Event):void{
			this.result = event.target.data;
			fb(this.result.toString());
			// this.addChild(this.result);
			
			this.event(Event.COMPLETE);
			
			return;
		}
		
		
		
		protected function _handleError(event:Event):void{
			this.fb(String(event));
			return;
		}
	}
}