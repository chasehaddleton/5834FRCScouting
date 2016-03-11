<?php
namespace ScoutingAPI {
	/**
	 * Created by PhpStorm.
	 * User: Chase
	 * Date: 2016-03-10
	 * Time: 8:20 PM
	 */
	class Error {
		public $msg;
		public $code;

		public function __construct($errorMsg, $errorCode) {
			$this->msg = $errorMsg;
			$this->code = $errorCode;
		}
	}
}
