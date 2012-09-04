package Cundd.GraphicObject.Button
{
	import flash.events.Event;
	import flash.events.MouseEvent;
	import flash.display.*;
	
	public class Rollover extends Abstract
	{
		public var state:Boolean = false;
		// public var animationRunning:Boolean = false;
		
		public static const CUNDD_BUTTON_ANIMATION_IN_LABEL:String = 'aniIn';
		public static const CUNDD_BUTTON_ANIMATION_OUT_LABEL:String = 'aniOut';
		
		public var externalHandling:Boolean = false;
		
		
		
		public function Rollover(arguments:Array=null)
		{
			super(arguments);
			this._init(arguments);
		}
		
		
		
		protected function _init(arguments:Array):void{
			this.addEventListener(MouseEvent.ROLL_OVER,this.aniIn);
			return;
		}
		
		
		
		public function aniIn(event:MouseEvent = null):void{
			if(this.externalHandling){
				this.dispatchEvent(new Event(Rollover.CUNDD_BUTTON_ANIMATION_IN_LABEL));
			} else if(!this.state && !this.isPlaying){
				this.state = true;
				this.addEventListener(MouseEvent.ROLL_OUT,this.aniOut);
				this.gotoAndPlay(Rollover.CUNDD_BUTTON_ANIMATION_IN_LABEL);
			}
			return;
		}
		
		
		
		public function aniOut(event:MouseEvent = null):void{
			if(this.externalHandling){
				this.dispatchEvent(new Event(Rollover.CUNDD_BUTTON_ANIMATION_OUT_LABEL));
			} else if(this.state && !this.isPlaying){
				this.gotoAndPlay(Rollover.CUNDD_BUTTON_ANIMATION_OUT_LABEL);
				this.removeEventListener(MouseEvent.ROLL_OUT,this.aniOut);
				this.state = false;
				return;
			}
		}
		
		
		public function key(event:Event){
			echo(this.currentLabel);
		}
		
	}
}