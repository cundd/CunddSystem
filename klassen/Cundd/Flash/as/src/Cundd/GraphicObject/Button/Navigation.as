package Cundd.GraphicObject.Button
{
	import Cundd.GraphicObject.Abstract;
	
	import flash.display.MovieClip;
	import flash.events.MouseEvent;

	public class Navigation extends Abstract
	{
		public var targetLabel:String = '';
		public var target:MovieClip = null;
		
		/**
		 * 'play'|'stop' Defines if the click-event performs a "gotoAndPlay" or "gotoAndStop" function.
		 */
		public var gotoMode:String = 'play';
		
		
		
		public function Navigation(arguments:Array=null)
		{
			super(arguments);
			this._init(arguments);
		}
		
		
		
		protected function _init(arguments:Array):void{
			this.addEventListener(MouseEvent.CLICK,this.click);
		}
		
		
		
		public function click(event:MouseEvent = null):void{
			if(!this.target){
				this.target = this.root as MovieClip;
			}
			
			if(event && !this.targetLabel){
				if(event.target.hasOwnProperty('name')){
					this.targetLabel = event.target.name;
				}
			}
			
			if(this.target && this.gotoMode == 'play'){
				this.target.gotoAndPlay(this.targetLabel);
			} else if(this.target && this.gotoMode == 'stop'){
				this.target.gotoAndStop(this.targetLabel);
			} else if(!this.target)){
				this.error('No target set');
			} else {
				this.error('Incorrect gotoMode'+this.gotoMode);
			}
		}
		
	}
}