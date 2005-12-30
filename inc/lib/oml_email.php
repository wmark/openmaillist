<?php
/**
 * Contains specific methods for OML which need not be general applicable to emails.
 *
 * @see		<a href="http://www.ietf.org/rfc/rfc2183.txt">RFC 2183</a>
 * @see		<a href="http://www.emaillab.org/essay/japanese-filename.html">Japanese Filename</a>
 * @todo	Implement filenames consisting of non-latin-1 chars.
 */
class oml_email
	extends MIME_Mail
{
	/** We must know where to write the attachments. */
	protected	$attachment_dir	= '/tmp';

	/**
	 * This functions set where attachments will be written to.
	 *
	 * @param $where	Has to be the absolute path without trailing slash to the location where the attachments will be stored.
	 * @return		True if the given path exists, is a directory and writeable.
	 */
	public function set_attachment_storage($where) {
		if(is_dir($where) && is_writable($where)) {
			$this->attachment_dir = $where;
			return true;
		} else {
			return false;
		}
	}

	/**
	 * These are administrative emails:
	 * - disposition notifications
	 * - notices about delete mailboxes
	 * - in general, everything not written by a human
	 *
	 * @return		Boolean
	 */
	public function is_administrative() {
		if((isset($this->header['content-type']) && strstr($this->header['content-type'], 'report'))
		  || (isset($this->header['x-failed-recipients']))
		  || (isset($this->header['return-path']) && strstr(strtolower($this->header['return-path']), 'mailer-daemon'))
		  ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @return		Boolean
	 */
	public function is_disposition_notification() {
		if(isset($this->header['content-type']) && strstr($this->header['content-type'], 'disposition-notification')) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @return		Boolean whether writing was successfull.
	 */
	public function write_attachments_to_disk() {
		$att	= $this->get_attachments();
		$t	= true;

		foreach($att as $name=>$data) {
			$filename = $this->attachment_dir.'/'.$name;
			if(is_file($filename)) {
				$t	= false;
			} else {
				file_put_contents($filename, $data);
			}
		}
		return $t;
	}

}
?>