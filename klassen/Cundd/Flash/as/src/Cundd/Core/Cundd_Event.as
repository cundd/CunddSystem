package Cundd.Core
{
	import flash.events.Event;

	public class Cundd_Event extends flash.events.Event
	{
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
		
		
		
		public function Cundd_Event(type:String, bubbles:Boolean=false, cancelable:Boolean=false)
		{
			super(type, bubbles, cancelable);
		}
		
	}
}