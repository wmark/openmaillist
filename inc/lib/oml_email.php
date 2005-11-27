<?php
/**
 * This class is an specialization for OML. It's purposes are:
 * - Taking raw messages and analyzing them.
 * - Be passed as parameter in OML methods.
 */

include('Mail/mimeDecode.php');

class oml_email {

// private:
	var	$mime_message;
	var	$structure;
	var	$hoi		= array();	// headers of interest
	var	$analyzed	= false;
	var	$parts		= array();	// MIME parts including trailer.

// public:
	function oml_email(&$raw_message) {
		$this->mime_message = new Mail_mimeDecode($raw_message, "\r\n");
	}

	/**
	 * Analyzes the message.
	 * Does not trust the "Date"-Field.
	 * @return	false; If the given message turns out to not comply with standards.
	 */
	function study() {
		// This way we receive an hash with headers.
		$this->structure = $this->mime_message->decode();

		// Now we have to pick all the interesting values from the headers.
		$this->hoi['message-id']	= substr($this->structure->headers['message-id'], 1, -1);
		$this->hoi['from']		= $this->structure->headers['from'];
		$this->hoi['date-received']	= strtotime(substr(strrchr($this->structure->headers['received'][0], ';'), 2));
		$this->hoi['subject']		= $this->structure->headers['subject'];

		$this->hoi['_recipient']	= $this->structure->headers['to'];

		if(isset($this->structure->headers['in-reply-to'])) {
			$this->hoi['in-reply-to']	= substr($this->structure->headers['in-reply-to'], 1, -1);
		}
		if(isset($this->structure->headers['references'])) {
			$this->hoi['references']	= $this->structure->headers['references'];
		}

		// Get the chronological first "Received"-entry.
		$i = count($this->structure->headers['received']) - 1;
		if($i > 0) {
			$this->hoi['date-send']		= strtotime(substr(strrchr($this->structure->headers['received'][$i], ';'), 2));
		}
		else {
			$this->hoi['date-send']		= $this->hoi['date-received'];
		}

		$this->analyzed = true;

		return true;
	}

	function get_header($key) {
		if(!$this->analyzed) {
			if(!$this->study()) {
				return '';
			}
		}

		if(isset($this->hoi[$key])) {
			return $this->$hoi[$key];
		}
		else if(isset($this->structure->headers[$key])) {
			return $this->structure->headers[$key];
		}
		else {
			return '';
		}
	}

	function get_entire_header() {
		return $this->mime_message->_header;
	}

	function get_entire_body() {
		return $this->mime_message->_body;
	}

	// private
	function split_parts() {
		$this->parts = explode(	$this->structure->ctype_parameters['boundary'],
					$this->get_entire_body());
		array_walk($this->parts,
				create_function('&$item,$index',
						'$item = trim($item);'
						));
	}

	function get_first_part() {
		// In case we have no attachements the task will be trivial.
		if(!$this->has_attachements()) {
			return $this->get_entire_body();
		}
		else {
			// Else we are to split the body in corresponding mime parts and return first text/text-plain part.
			$this->split_parts();


		}
	}

	function has_attachements() {
		return ($this->structure->ctype_secondary != 'plain');
	}
}
?>