<?php
namespace ScoutingAPI {
	class Error {
		public $msg;
		public $code;

		public function __construct($errorMsg, $errorCode) {
			$this->msg = $errorMsg;

			if (substr($errorMsg, strlen($errorMsg) -1)) {
				$this->msg .= ". ";
			}

			$this->msg .= "Refer to API documentation for details.";
			$this->code = $errorCode;
		}
	}
}
