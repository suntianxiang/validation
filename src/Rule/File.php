<?php

namespace Validation\Rule;

use Lib_UploadFile;
use Validation\Validator;

/**
 * file rule
 *
 * @author suntianxiang <suntianxiang@tiange.com>
 */
class File implements Rule
{
    /** @var int file max size */
    protected $maxSize;

    /** @var int file min size */
    protected $minSize;

    /** @var array allowed mime type */
    protected $mimeTypes;

    /** @var bool does the file required? */
    protected $required;

    /** @var int max uploaded file count */
    protected $maxFiles;

    /** @var int min upoloaded file count */
    protected $minFiles;

    /** @var string error message */
    private $message;

    public function __construct($maxSize, $minSize = null, $mimeTypes = null, $required = false, $maxFiles = null, $minFiles = null)
    {
            $this->maxSize = $maxSize;
            $this->minSize = $minSize;
            $this->mimeTypes = $mimeTypes;
            $this->required = $required;
            $this->maxFiles = $maxFiles;
            $this->minFiles = $minFiles;
    }

    public function getName()
    {
        return 'file';
    }

    public function pass($value, Validator $context)
    {
        if (!isset($_FILES[$value])) {
            if ($this->required) {
                $this->message = 'no file uploaded';
                return false;
            }

            return true;
        }

        
        if ($this->maxFiles && count($_FILES[$value]['name']) > $this->maxFiles) {
            $this->message = "uploaded too many files, limit $this->maxFiles";
            return false;
        }

        if ($this->minFiles && count($_FILES[$value]['name']) < $this->minFiles) {
            $this->message = "to few files uploaded, limit $this->minFiles";
            return false;
        }

        foreach ($_FILES[$value]['name'] as $i => $name) {
            if ($this->maxSize && $this->maxSize < $_FILES[$value]['size'][$i]) {
                $this->message = "$name size must less than $this->maxSize";
                return false;
            }
    
            if ($this->minSize && $this->minSize > $_FILES[$value]['size'][$i]) {
                $this->message = "$name file size must greate than $this->minSize";
                return false;
            }
    
            if ($this->mimeTypes && !in_array(mime_content_type($_FILES[$value]['tmp_name'][$i]), $this->mimeTypes)) {
                $this->message = "$name invalid file type, allow:".implode(';', $this->mimeTypes);
                return false;
            }
        }

        return true;
    }

    public function getMessage()
    {
        return $this->message;
    }
}