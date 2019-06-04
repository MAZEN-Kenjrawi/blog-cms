<?php

namespace App\Controller\Admin;

use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
	/**
	 * Froala: uploading image process
	 * 
	 * @Route("admin/api/upload/{file_type}/type", name="admin_froala_upload")
	 */
	public function upload_image(Request $request, FileUploader $uploader, string $file_type)
	{
		// Set file type [image, file]
		$uploader->setType($file_type);
		// File, $_FILES[$fieldname]["name"]
		$file = $request->files->get('file');
		// Validate the file extension
		if (!$uploader->isValid($file)) {
			return new Response('Invalid file!', Response::HTTP_UNPROCESSABLE_ENTITY);
		}

		// Save File
		$file_link = $uploader->upload($file);
		$uploaded_file = new \stdClass();
		$uploaded_file->link = $request->getUriForPath('/uploads/' . $file_link);

		// Return file link as a strng, $response->link
		return new JsonResponse($uploaded_file, Response::HTTP_OK);
	}

	/**
	 * Froala: list images process
	 * 
	 * @Route("/admin/api/load/{file_type}/type", name="admin_froala_load")
	 */
	public function load(Request $request, FileUploader $uploader, string $file_type)
	{
		$upload_dir = $uploader->getUploadDirectory($file_type);
		$all_files = [];
		// Scan upload dir depending on which type
		$dir_content = scandir($upload_dir);
		foreach ($dir_content as $file) {
			if (!is_file($upload_dir . '/' . $file)) {
				continue;
			}
			$file_obj = new \stdClass();
			$file_obj->url = $upload_dir . '/' . $file;
			$file_obj->thumb = $request->getUriForPath('/uploads/' . $file_type . '/' . $file);
			$file_obj->name = $file;

			$all_files[] = $file_obj;
		}

		return new JsonResponse($all_files, Response::HTTP_OK);
	}

	/**
	 * Froala: deleting image process
	 * 
	 * @Route("/admin/api/delete/{file_type}/type", name="admin_froala_delete")
	 */
	public function delete_image(Request $request, FileUploader $uploader, string $file_type)
	{
		// File src
		$file = $request->request->get('src');
		$file = basename($file);
		$upload_dir = $uploader->getUploadDirectory($file_type);
		if (is_file($upload_dir . '/' . $file)) {
			unlink($upload_dir . '/' . $file);
		}

		return new JsonResponse('success', Response::HTTP_OK);
	}

	public function change_status(string $type, int $id)
	{
		return new JsonResponse(['type' => $type, 'id' => $id], Response::HTTP_OK);
	}
}
