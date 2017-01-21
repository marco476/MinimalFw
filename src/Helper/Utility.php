<?php
namespace Helper;

class Utility
{
	static public function set404()
	{
		header("HTTP/1.1 404 Not Found");
		exit();
	}
}