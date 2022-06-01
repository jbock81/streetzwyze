<?php

class FileUploadHandler extends IdeoObject
{

    protected $_max_file_size;
    protected $_allowed_extensions;
    protected $_field_name;
    protected $_is_moved = false;
    protected $_new_file_path;
    protected $_is_valid = false;
    public $error = -1;

    const ERR_NO_FILE_UPLOADED = 20001;
    const ERR_FILE_TOO_LARGE = 20002;
    const ERR_INVALID_TYPE = 20003;
    const ERR_UPLOAD_FAILED = 30001;

    /**
     * FileUploadHandler::__construct()
     *
     * @param mixed $fieldName
     * @param mixed $inputConfig containing config for max_file_size, allowed_extensions, etc
     * @return void
     */
    public function __construct($fieldName, $inputConfig = null)
    {
        $appConfig = AppConfig::getInstance();
        $this->_field_name = $fieldName;
        if (isset($inputConfig['max_file_size'])) {
            $this->_max_file_size = $inputConfig['max_file_size'];
        } else {
            $this->_max_file_size = $appConfig->maxFileUploadSize * 1024;
        }

        if (isset($inputConfig['allowed_extensions'])) {
            $this->_allowed_extensions = is_array($inputConfig['allowed_extensions']) ? $inputConfig['allowed_extensions'] : explode(',', $inputConfig['allowed_extensions']);
        } else {
            $this->_allowed_extensions = explode(',', $appConfig->allowedPictureExts);
        }
    }

    public function getFileExtension()
    {
        return strtolower(end(explode('.', $this->getFileName())));
    }

    public function move_to_folder($filename = '', $folder = '')
    {
        if (!$filename) {
            $filename = $this->getFileName();
        }

        if ($folder && !preg_match('@[\\\/]$@', $folder)) {
            $folder .= DIRECTORY_SEPARATOR;
        }

        return $this->moveToPath($folder . $filename);
    }

    public function moveToPath($path)
    {
        $this->_is_moved = move_uploaded_file($this->getTmpFileName(), $path);
        if ($this->_is_moved) {
            $this->_new_file_path = $path;
        }
        return $this->_is_moved;
    }

    public function getTmpFileName()
    {
        return $_FILES[$this->_field_name]['tmp_name'];
    }

    public function getFileName()
    {
        return $_FILES[$this->_field_name]['name'];
    }

    public function getFileSize()
    {
        return $_FILES[$this->_field_name]['size'];
    }

    public function getUploadError()
    {
        return $_FILES[$this->_field_name]['error'];
    }

    public function getFileContent()
    {
        $path = $this->_is_moved ? $this->_new_file_path : $this->getTmpFileName();
        return file_get_contents($path);
    }

    public function isValid()
    {
        return $this->_is_valid;
    }

    public function validateUpload()
    {
        $error = false;
        if ($this->getUploadError() == UPLOAD_ERR_OK) {
            if (!$this->isFileUploaded()) {
                $error = self::ERR_NO_FILE_UPLOADED;
            }

            if (!$error && ($this->getFileSize() > $this->_max_file_size)) {
                $error = self::ERR_FILE_TOO_LARGE;
            }

            if (!$error && count($this->_allowed_extensions) && !in_array($this->getFileExtension(), $this->_allowed_extensions)) {
                $error = self::ERR_INVALID_TYPE;
            }
        } else {
            $error = $this->getUploadError();
        }


        $this->error = $error;
        return ($this->_is_valid = !$error);
    }

    public function isFileUploaded()
    {
        return $this->getFileSize();
    }

    public function getFieldName()
    {
        return $this->_field_name;
    }

    public function getAllowedExtensionsAsString()
    {
        return join(", ", $this->_allowed_extensions);
    }

}