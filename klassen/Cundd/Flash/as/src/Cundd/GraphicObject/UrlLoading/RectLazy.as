package Cundd.GraphicObject.UrlLoading
{
	public class RectLazy extends Rect
	{
		protected var _protectedOne:String;
		/**
		 * The object is lazy because zoom- and drag-options are disabled.
		 * @param arguments
		 * 
		 */
		public function RectLazy(arguments:Array = null)
		{
			if(arguments){
				super(arguments);
				this._cundd_drag_isDragable = false;
				this._cundd_zoom_isZoomable = false;
				this._cundd_drag_keepOnStage = false;
			}
		}
		
	}
}