<?php
namespace ScoutingAPI {
	class Error {
		public $msg;
		public $code;

		public function __construct($errorMsg, $errorCode) {
			$this->msg = $errorMsg;
			$this->code = $errorCode;
		}
	}
}
