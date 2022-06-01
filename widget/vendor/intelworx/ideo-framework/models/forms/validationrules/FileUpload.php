<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace models\forms\validationrules;

/**
 * Description of FileUpload
 *
 * @author intelWorX
 */
class FileUpload extends \models\forms\ValidationRule
{

    /**
     *
     * @var \FileUploadHandler
     */
    protected $uploadHandler;

    const UPLOAD_TYPE_IMAGES = 'images';
    const UPLOAD_TYPE_DOCS = 'documents';
    const UPLOAD_LIST = 'list';

    public static $EXTENSIONS = array(
        self::UPLOAD_TYPE_IMAGES => array('jpg', 'jpeg', 'png', 'gif', 'bmp'),
        self::UPLOAD_TYPE_DOCS => array('doc', 'docx', 'txt', 'rtf', 'pdf'),
        self::UPLOAD_LIST => array('csv', 'tsv', 'txt'),
    );

    const MAX_UPLOAD_SIZE = 2097152;

    protected $isRequired = false;

    public function __construct($uploadField, $isRequired, $upload_type = null, $extensions = array(), $maxSize = self::MAX_UPLOAD_SIZE)
    {
        if (empty($extensions) && $upload_type) {
            $extensions = self::$EXTENSIONS[$upload_type];
        }
        $this->uploadHandler = new \FileUploadHandler($uploadField, array(
            'allowed_extensions' => $extensions,
            'max_file_size' => $maxSize,
        ));

        $this->isRequired = $isRequired;
    }

    public function validate($inputValue)
    {
        $valid = $this->uploadHandler->validateUpload();
        if (!$valid) {
            $key = 'upload.e' . $this->uploadHandler->error;
            $this->error = \Strings::get($key);
        }

        //file is uploaded but upload not valid?
        if ($this->uploadHandler->isFileUploaded() && !$valid) {
            return false;
        }

        return !$this->isRequired || ($this->isRequired && $valid);
    }

    /**
     *
     * @return \FileUploadHandler
     */
    public function getUploadHandler()
    {
        return $this->uploadHandler;
    }

    public function isUploaded()
    {
        return $this->uploadHandler->isFileUploaded();
    }

}
