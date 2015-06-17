<?php


class Fl
{
	/**
	 * Base directory
	 * 
	 * @var string
	 */
	private $baseDir;

	/**
	 * State initialization
	 * 
	 * @param string $rootDir
	 * @param string $baseDir
	 * @return void
	 */
	public function __construct($rootDir, $baseDir)
	{
		$this->baseDir = $baseDir;
		$this->rootDir = $rootDir;
	}

	/**
	 * Uploads a file
	 * 
	 * @param string $id
	 * @param array $files
	 * @return boolean
	 */
	public function upload($id, array $files)
	{
		if (!empty($files)) {
			$uploader = new FileUploader();

			// Target destination path
			$destination = $this->rootDir . $this->baseDir;

			foreach ($files as $file) {
				if (!$uploader->upload($destination, $files)) {
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Removes a file
	 * 
	 * @param string $id
	 * @param string $filename
	 * @return boolean
	 */
	public function remove($id, $filename = null)
	{
		
	}
}
