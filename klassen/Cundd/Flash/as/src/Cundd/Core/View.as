package Cundd.Core
{
	import flash.display.InteractiveObject;
	import flash.events.MouseEvent;
	import flash.geom.Rectangle;
	
	/**
	 * The class offers zoom-support without the use of the 3D-features of Flash Player version 10.
	 * @author daniel
	 * 
	 */
	public class View extends Cundd_Core_Object
	{
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		/* ZOOM */
		protected var _cundd_zoom_isZoomable:Boolean = true;
		protected var _cundd_zoom_zoomFactor:Number = 10; // Percent of in-/decrease in one zoom-step
		protected var _cundd_zoom_zoomMinSize:Number = 1; // Defines the minimal allowed zoom-factor; -1 stands for disabled
		protected var _cundd_zoom_zoomMaxSize:Number = 1000; // Defines the maximal allowed zoom-factor; -1 stands for disabled
		
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		/* SCROLL */
		protected var _cundd_scroll_isScrollable:Boolean = true;
		
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		/* DRAG */
		protected var _cundd_drag_isDragable:Boolean = true;
		/**
		 * 'none'|'ctrl'|'shift'|'cmd' Defines if a key has to be pressed to enable dragging.
		 */
		protected var _cundd_drag_needKey:String = 'none';
		protected var _cundd_drag_startX:int;
		protected var _cundd_drag_startY:int;
		protected var _cundd_drag_startTime:Date;
		protected var _cundd_drag_frameCounter:int;
		protected var _cundd_drag_frameGap:int = 10;
		protected var _cundd_drag_boundingRect:Rectangle;
		
		/**
		 * Defines if the object allways have to cover the whole stage when draging.
		 */
		protected var _cundd_drag_keepFillingScreen:Boolean = false;
		
		/**
		 * Defines if the object must not be dragged away from the stage (even partly).
		 */
		protected var _cundd_drag_keepOnStage:Boolean = false;
		
		protected var _cundd_drag_tolerance:int = 10;
		
		
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//
		// INIT
		//
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		/**
		 * Cundd_Core_View provides an basic MovieClip-Class which serves builtin drag- and zoom-support.
		 * 
		 */
		public function View(arguments:Array = null)
		{
			super(arguments);
			this.__init(arguments);
		}
		
		
		
		/**
		 * Die Methode führt die nötigen Befehle zur Initialisierung aus.
		 * 
		 */
		private function __init(arguments:Array):void{
			this._registerProperties(arguments);
			
			if(this._cundd_zoom_isZoomable){
				this.addEventListener(MouseEvent.MOUSE_WHEEL,this._zoomFromMouseWheel);
			}
			if(this._cundd_scroll_isScrollable){
				
			}
			if(this._cundd_drag_isDragable){
				this.addEventListener(MouseEvent.MOUSE_DOWN,this._dragFromMouseDown);
			}
			return;
		}
		
		
		
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//
		// DRAG
		//
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		protected function _dragFromMouseDown(event:MouseEvent):void{
			var allowDrag:Boolean = false;
			
			this.fb(this._cundd_drag_needKey)
			
			if(this._cundd_drag_isDragable){
				// In this._cundd_drag_needKey wird definiert ob und welcher Key zum Drag gedrückt sein muss.
				switch(this._cundd_drag_needKey){
					case 'none':
						allowDrag = true;
						break;
						
					case 'ctrl':
						if(event.ctrlKey){allowDrag = true;}
						break;
						
					case 'shift':
						if(event.shiftKey){allowDrag = true;}
						break;
						
					case 'cmd':
						// Property is documented but shows an error if(event.commandKey){allowDrag = true;}
						if(event.ctrlKey){allowDrag = true;}
						break;
						
					default:
						break;
				}
			}
			
			
			// allowDrag = this._cundd_drag_checkIfKeepFillingScreen(allowDrag);
			
			
			if(allowDrag){ // If ctrl-key or on Mac ctrl, or command-key is pressed
				this._cundd_drag_startDrag();
				this.addEventListener(MouseEvent.MOUSE_UP,this._drag_stop);
			}
			
			
			/* @TODO work without special key
			this._cundd_drag_startX = event.localX + event.target.x;
			this._cundd_drag_startY = event.localY + event.target.y;
			this._cundd_drag_startTime = new Date();
			
			
			// this.addEventListener(MouseEvent.MOUSE_MOVE,_dragMouseMove);
			// this.addEventListener(MouseEvent.MOUSE_MOVE,_drag_MouseUp);
			// this.addEventListener(Event.ENTER_FRAME,_drag_frameCounter);
			// protected var _cundd_drag_tolerance:int = 10;
			/* */
			return;
		}
		
		
		
		
		
		
		protected function _cundd_drag_startDrag():void{
			if(this._cundd_drag_getBoundingRect()){
				this.startDrag(false,this._cundd_drag_getBoundingRect());
			} else {
				this.startDrag();
			}
		}
		
		
		
		/**
		 * Die Methode überprüft ob das Display-Element immer die gesammte Stage ausfüllen muss. 
		 * Dazu wird überprüft ob das Element die Stage überhaupt ausfüllen kann und ob das Feature 
		 * zur Überprüfung aktiviert ist (_cundd_drag_keepFillingScreen = true). Anschließend wird 
		 * ein EventListener gesetzt der dies fortlaufend überprüft.
		 * @return 
		 * 
		 */
		 protected function _cundd_drag_checkIfKeepFillingScreen():Boolean{
		 	// return this._cundd_drag_keepFillingScreen;
			var returnVal:Boolean;// = allowDragSoFar;
			
			if(!this._cundd_drag_keepFillingScreen){ // Feature is deactivated
				returnVal = false;
			} else {
				var r:Number = this.rotation
				var width:Number = this.width;
				var height:Number = this.height;
				
				var sWidth:Number = this._getStageWidth()
				var sHeight:Number = this._getStageHeight()
				
				// CunddSystem.firebug('check'+this.rotation + ' '+this._getStageWidth() + ' ' + this.width + ' ' + this._getStageHeight() + ' ' + this.height + (this.width > this._getStageWidth()) + (this.height > this._getStageHeight()))
				if(this.width >= this._getStageWidth() && this.height >= this._getStageHeight()){ // Check if the object is big enough
					this._cundd_drag_getBoundingRect();
					returnVal = true;
				} else {
					returnVal = false;
				}
			}
			
			return returnVal;
		}
		/* */
		
		
		
		/**
		 * Die Methode erstellt das Rechteck das den Drag-Bereich der View definiert. Dazu überprüft es 
		 * welchen Modus zur Bestimmung des Rechtecks es wählen muss.
		 * @return 
		 * 
		 */
		protected function _cundd_drag_getBoundingRect():Rectangle{
			var bRWidth:Number;
			var bRHeight:Number;
			var bRX:Number;
			var bRY:Number;
			var createRect:Boolean = false;
				/*
				bRWidth = 
				bRHeight = 
				bRX = 
				bRY = 
				*/
			
			if(this._cundd_drag_checkIfKeepFillingScreen()){
				bRWidth = 2 * this.width - this._getStageWidth();
				bRHeight = 2 * this.height - this._getStageHeight();
				bRX = -(this.width - this._getStageWidth());
				bRY = -(this.height - this._getStageHeight());
				createRect = true;
			} else if(this._cundd_drag_keepOnStage){
				bRWidth = this._getStageWidth();
				bRHeight = this._getStageHeight();
				bRX = 0;
				bRY = 0;
				createRect = true;
			}
			
			if(createRect){
				this._cundd_drag_boundingRect = new Rectangle(bRX,bRY,bRWidth,bRHeight);
			} else {
				this._cundd_drag_boundingRect = null;
			}
			
			
			return this._cundd_drag_boundingRect;
		}
		
		
		
		protected function _getStageWidth():Number{
			return this.stage.width;
		}
		protected function _getStageHeight():Number{
			return this.stage.height;
		}
		
		
//		/**
//		 * 
//		 * @param event
//		 * 
//		 */
//		protected function _cundd_drag_checkIfObjectWouldStopFillingScreen(event:MouseEvent):void{
//			this.fb('start chek')
//			this.fb(this.rotation +' '+ this.x +' '+ this.width +' '+ this._getStageWidth + String(this.x + this.width >= this._getStageWidth))
//			this.fb(String(this.y + this.height >= this._getStageHeight()))
//			
//			var badLeft:Boolean = false;
//			var badRight:Boolean = false;
//			var badTop:Boolean = false;
//			var badBottom:Boolean = false;
//			var badString:String = '';
//			
//			
//			if(this.x <= 0){ /* ist links */
//				badLeft = true;
//				badString += 'left ';
//			}
//			if(this.y <= 0){ /* ist oben */
//				badTop = true;
//				badString += 'top ';
//			}
//			if(this.x + this.width >= this._getStageWidth){ /* ist rechts */
//				badRight = true;
//				badString += 'right ';
//			}
//			if(this.y + this.height >= this._getStageHeight()){/* ist unten */
//				badBottom = true;
//				badString += 'bottom ';
//			}
//			
//			if(badString){ // Ein Fehler ist aufgetreten
//				this.fb(badString);
//				this.removeEventListener(MouseEvent.MOUSE_UP,this._drag_stop);
//				this._drag_stop();
//			}
///*			if(this.x <= 0 && /* ist links */
///*				this.y <= 0 && /* ist oben */
///*				this.x + this.width >= this._getStageWidth && /* ist rechts */
///*				this.y + this.height >= this._getStageHeight/* ist unten */
///*				){
//					fb('is ok')
//					// Move ist ok
//				} else { // Move ist nicht ok
//				fb('not ok')
//					this.removeEventListener(MouseEvent.MOUSE_UP,this._drag_stop);
//					this._drag_stop();
//				}
//			/* */
//		}
		
		
		
		/**
		 * Die Methode stoppt den Drag-Vorgang.
		 * @param event
		 * 
		 */
		protected function _drag_stop(event:MouseEvent = null):void{
			/* if(this.hasEventListener(MouseEvent.MOUSE_MOVE)){
				fb('this.removeEventListener(MouseEvent.MOUSE_MOVE')
				this.removeEventListener(MouseEvent.MOUSE_MOVE,this._cundd_drag_checkIfObjectWouldStopFillingScreen);
			} */
			this.stopDrag();
		}
		
		
		/* @TODO work without special key
		protected function _drag_frameCounter(event:Event):void{
			// Die frameCounter wartet ein paar Frames bis er die Position des Maus-Zeigers mit der Startposition vergleicht
			if(this._cundd_drag_frameCounter < this._cundd_drag_frameGap){
				this._cundd_drag_frameCounter++;
				return;
			} else { // Vergleichen
				
				CunddSystem.firebug(String(event.target))
			}
		}
		
		
		
		protected function _dragMouseMove(event:MouseEvent):void{
			this._drag_frameCounter(event);
//			CunddSystem.firebug(String(this._cundd_drag_startTime.getSeconds()));
			CunddSystem.firebug(String(event.localX))
		}
		/* */
		
		
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//
		// CLICK
		//
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		protected function _click(event:MouseEvent):void{
			CunddSystem.firebug(event.target);
		}
		
		
		
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//
		// ZOOM
		//
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		/**
		 * Die Methode fängt den Maus-Rad-Event.
		 * @param event
		 * @return 
		 * 
		 */
		protected function _zoomFromMouseWheel(event:MouseEvent):void{
			if(this._cundd_zoom_isZoomable){
				this.zoom(event.delta);
			}
			return;
		}
		
		
		
		/**
		 * Die Methode skaliert diese Instanz auf 100 + steps * this._cundd_zoom_zoomFactor in %.
		 * @param steps
		 * @param target[optional]
		 * @return 
		 * 
		 */
		public function zoom(steps:int,target:InteractiveObject = null):void{
			if(this._cundd_zoom_isZoomable){
				if(!target){
					target = this;
				}
				var newX:Number;
				var newY:Number;
				var appendScale:Boolean
				appendScale = false;
				
				
				var newScale:Number = (100 + steps * this._cundd_zoom_zoomFactor) / 100;
				// CunddSystem.firebug(String(newScale))
				newX = target.scaleX * newScale;
				newY = target.scaleY * newScale;
				
				// Check if it is not to small or to big
				if(this._cundd_zoom_zoomMinSize != -1){ // Append min-size
					if(newX >= this._cundd_zoom_zoomMinSize / 100 && newY >= this._cundd_zoom_zoomMinSize / 100){
						appendScale = true;
					} else {
						// var toSmallMsg:String = 'To small to zoom. New target\'s scale would be x:'+newX+' y:'+newY+' @'+CunddSystem.time();
						// this.fb(toSmallMsg);
						appendScale = false;
					}
				}
				if(this._cundd_zoom_zoomMaxSize != -1 && appendScale){ // Append max-size
					if(newX <= this._cundd_zoom_zoomMaxSize / 100 && newY <= this._cundd_zoom_zoomMaxSize / 100){
						appendScale = true;
					} else {
						// var toBigMsg:String = 'To big to zoom. New target\'s scale would be x:'+newX+' y:'+newY+' @'+CunddSystem.time();
						// this.fb(toBigMsg);
						appendScale = false;
					}
				}
				if(appendScale == true){
					// fb(toBigMsg+toSmallMsg+'Appended at time '+CunddSystem.time() + ' ' + appendScale);
					target.scaleX = newX;
					target.scaleY = newY;
				}
				
				return;
			}
		}
		
		
	}
}