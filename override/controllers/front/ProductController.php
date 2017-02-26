<?php

class ProductController extends ProductControllerCore
{
	const CUSTOMIZATION_FILE_DIR = 'customizations';

	protected function pictureUpload()
	{
		if (!($field_ids = $this->product->getCustomizationFieldIds()))
			return false;
		$authorized_file_fields = array();
		foreach ($field_ids AS $field_id)
		{
			if ($field_id['type'] == Product::CUSTOMIZE_FILE)
				$authorized_file_fields[(int)$field_id['id_customization_field']] = 'file' . (int)$field_id['id_customization_field'];
		}

		$indexes = array_flip($authorized_file_fields);
		foreach ($_FILES AS $field_name => $file)
		{
			if (in_array($field_name, $authorized_file_fields) AND isset($file['tmp_name']) AND !empty($file['tmp_name']))
			{
				// If there is an upload error, let the parent handle it
				if ($file['error'] != UPLOAD_ERR_OK)
					continue;

				// If the file is not allowed, let the parent handle it
				if (!$this->isUploadTypeAllowed($file))
					continue;

				// Unset the PDF to prevent the parent to handle this file
				unset($_FILES[$field_name]);

				// Create dir
				mkdir(_PS_UPLOAD_DIR_ . ProductController::CUSTOMIZATION_FILE_DIR.'/'.$this->context->cart->id, 0777, true);

				// Mark the file as a custom upload
				$file_name = ProductController::CUSTOMIZATION_FILE_DIR.'/'.$this->context->cart->id.'/P'. md5(uniqid(rand(), true));
				$tmp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS');
				if (!move_uploaded_file($file['tmp_name'], $tmp_name))
				{
					$this->errors[] = Tools::displayError('An error occurred during the PDF upload.');
					return false;
				}
				// Copy file to the upload dir
				if (!copy($tmp_name, _PS_UPLOAD_DIR_.$file_name))
				{
					$this->errors[] = Tools::displayError('An error occurred during the PDF upload.');
					return false;
				}
				// Chmod the new file
				if (!chmod(_PS_UPLOAD_DIR_.$file_name, 0777))
				{
					$this->errors[] = Tools::displayError('An error occurred during the PDF upload.');
					return false;
				}

				// Create a fake thumb to avoid error on delete, this hack avoids lots of core method override
				file_put_contents(_PS_UPLOAD_DIR_ . $file_name . '_small', '');
				chmod(_PS_UPLOAD_DIR_ . $file_name . '_small', 0777);

				// Register the file
				$this->context->cart->addPictureToProduct($this->product->id, $indexes[$field_name], Product::CUSTOMIZE_FILE, $file_name);

				// Remove tmp file
				unlink($tmp_name);
			}
		}
		return parent::pictureUpload();
	}

	protected function isUploadTypeAllowed($file)
	{
		/* Detect mime content type */
		$mime_type = false;
		$types = array('application/pdf',
		'application/msword',
		'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'text/plain',
		'application/vnd.ms-excel',
); // Extra mime types can be added here

		if (function_exists('finfo_open'))
		{
			$finfo = finfo_open(FILEINFO_MIME);
			$mime_type = finfo_file($finfo, $file['tmp_name']);
			finfo_close($finfo);
		}
		elseif (function_exists('mime_content_type'))
		{
			$mime_type = mime_content_type($file['tmp_name']);
		}
		elseif (function_exists('exec'))
		{
			$mime_type = trim(exec('file -b --mime-type '.escapeshellarg($file['tmp_name'])));
		}
		if (empty($mime_type) || $mime_type == 'regular file')
		{
			$mime_type = $file['type'];
		}

		if (($pos = strpos($mime_type, ';')) !== false)
			$mime_type = substr($mime_type, 0, $pos);
		// is it allowed?
		return $mime_type && in_array($mime_type, $types);
	}
    }
