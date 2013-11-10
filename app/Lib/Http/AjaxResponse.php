<?php

/*
* Class: 
* Author: Fredrik Fahlstad
*/
class AjaxResponse implements Serializable
{
	protected $message = "";
	protected $status = true; /* Ok as default */
	protected $data;

	public function __construct()
	{
	}

	function serialize()
	{
		return json_encode(array(
				"status" => $this->status,
				"message" => $this->message,
				"data" => $this->data
			)
		);

	}

	function get()
	{
		return array(
			"status" => $this->status,
			"message" => $this->message,
			"data" => $this->data
		);
	}

	function unserialize($string)
	{
		return json_decode($string);
	}

	public function setData($data)
	{
		$this->data = $data;
	}

	public function setMessage($message)
	{
		$this->message = $message;
	}

	public function setStatus($status)
	{
		$this->status = $status;
	}


}