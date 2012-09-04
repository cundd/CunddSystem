package Cundd.GraphicObject.Button
{
	import Cundd.GraphicObject.Abstract;

	internal class Abstract extends Cundd.GraphicObject.Abstract
	{
		public function Abstract(arguments:Array=null)
		{
			super(arguments);
			this.stop();
			this.buttonMode = true;
		}
		
	}
}