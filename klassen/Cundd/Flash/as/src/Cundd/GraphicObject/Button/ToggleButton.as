package Cundd.GraphicObject.Button
{
	import flash.events.Event;
	import flash.events.MouseEvent;
	
	public class ToggleButton extends Abstract
	{
		public var state:Boolean = false;
		
		public static const CUNDD_BUTTON_ON_LABEL:String = 'on';
		public static const CUNDD_BUTTON_OFF_LABEL:String = 'off';
		
		public function ToggleButton(arguments:Array=null)
		{
			super(arguments);
			this._init(arguments);
		}
		
		
		
		protected function _init(arguments:Array):void{
			this.addEventListener(MouseEvent.CLICK,this.toggle);
			return;
		}
		
		
		
		public function toggle(event:MouseEvent):Boolean{
			if(this.state){ // Button ist auf ON
				this.gotoAndStop(ToggleButton.CUNDD_BUTTON_OFF_LABEL);
				this.state = false;
			} else { // Button ist auf OFF
				this.gotoAndStop(ToggleButton.CUNDD_BUTTON_ON_LABEL);
				this.state = true;
			}
			
			
			this.dispatchEvent(	new Event(Event.CHANGE,true) );
			
			return this.state;
		}
		
	}
}