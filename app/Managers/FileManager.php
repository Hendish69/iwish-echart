<?php
namespace App\Managers;

class FileManager
{
	private $base;

	public function __construct(string $base)
	{
		$this->base = $base;
	}

	public function getChatAttachmentDirectory()
	{
		return $this->base.'/upload/chat';
	}

	public function getFile(?string $path)
	{
		if (null === $path) {
			return null;
		}

		$path = str_replace('../apps/upload/', '', $path);

		return $this->base.'/upload/'.$path;
	}
}