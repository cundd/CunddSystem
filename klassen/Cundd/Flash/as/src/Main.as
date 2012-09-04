package {
	import Cundd.Core.View;
	import Cundd.Core.controllers.*;
	import Cundd.GraphicObject.UrlLoading.*;
	import Cundd.Tools.*;
	
	import flash.display.MovieClip;
	import flash.display.Sprite;
	import flash.events.Event;
	import flash.events.KeyboardEvent;
	import flash.events.MouseEvent;
	import flash.text.TextField;
	import flash.text.TextFieldType;
	
	import nl.demonsters.debugger.MonsterDebugger;
	

	public class Main extends MovieClip
	{
		internal var object:Cundd_Core_AbstractController;
		
		public var controller:*;
		public var abc:Cundd_Core_IndexController;
		public var container:View;
		public var tempoMat:Rect;
		public var tempofMat:RectLazy;
		public var textfield:TextField;
		
		public var debugger:MonsterDebugger;
			
		public function Main()
		{
			
			this.debugger = new MonsterDebugger(this);
			//new DocClass();
			
			this.container = new View(new Array({'_cundd_drag_keepFillingScreen':true},{'_cundd_drag_needKey':'none'}));
			this.addChild(this.container);
			
			this.createTextfield();
			
			//var requestString:String = 'http://aaa/Music/Flash/?artist=pink+floyd';
			var requestString:String = 'http://aaa.cundd.net/Music/Flash/?filterString=artist';
			
			var cunddSystem:CunddSystem = new CunddSystem(this);
			this.container.addChild(cunddSystem);
			//this.container.mouseChildren = false;
			
			this.controller = CunddSystem.getController('Core/Index',requestString);
			this.controller.addEventListener(Event.COMPLETE,this.completeFnc);
			
			this.container.addChild(this.controller);
			
			
			
			
			MonsterDebugger.trace(this,'hallo world');
			/* */
		}
		
		
		
		public function completeFnc(event:Event):void{
			CunddSystem.firebug('Complete');
			this.controller.addEventListeners(MouseEvent.CLICK,this.clickOnContainer);
			return;
		}
		
		
		
		public function createTextfield():void{
			var textContainer:Sprite = new Sprite();
			this.addChild(textContainer);
			
			this.textfield = new TextField();
			this.textfield.width = 200;
			this.textfield.height = 20;
			this.textfield.x = 20;
			this.textfield.y = 0;
			this.textfield.textColor = 0xFFFFFF;
			this.textfield.text = 'input';
			this.textfield.type = TextFieldType.INPUT;
			textContainer.addChild(this.textfield);
			
			var but:MovieClip = new MovieClip();
			but.graphics.beginFill(0x0000000);
			but.graphics.drawRect(0,0,20,20);
			textContainer.addChild(but);
			
			var back:Sprite = new Sprite();
			back.graphics.beginFill(0x0000000,0.5);
			back.graphics.drawRect(20,0,200,20);
			textContainer.addChildAt(back,0);
			
			
			but.addEventListener(MouseEvent.CLICK,this.newText);
			textfield.addEventListener(KeyboardEvent.KEY_DOWN,this.keyDown);
		}
		
		
		public function keyDown(e:KeyboardEvent):void{
			if(e.keyCode == 13){
				this.newText();
			} else {
				// CunddSystem.firebug(e.keyCode)
			}
		}
		
		
		
		public function newText(e:Event = null):void{
			var requestString:String = 'http://aaa.cundd.net/Music/Flash?artist='+this.textfield.text.replace(/ /g,'+');
			CunddSystem.firebug(requestString);
			this.controller.refresh(requestString);
		}
		
		
		
		public function clickOnContainer(event:MouseEvent):void{
			CunddSystem.firebug('g')
			CunddSystem.firebug(event.target.name)
		}
	}
}
