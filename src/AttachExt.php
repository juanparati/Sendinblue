<?php


namespace Juanparati\Sendinblue;


class AttachExt
{

    /**
     * Allowed extensions
     *
     * @see https://developers.sendinblue.com/v3.0/reference#sendtransacemail-1
     */
    const ALLOWED_EXTENSIONS = [
        'bmp'  ,
        'cgm'  ,
        'css'  ,
        'csv'  ,
        'doc'  ,
        'docm' ,
        'docx' ,
        'eps'  ,
        'ez'   ,
        'gif'  ,
        'htm'  ,
        'html' ,
        'ics'  ,
        'jpeg' ,
        'jpg'  ,
        'mobi' ,
        'msg'  ,
        'ods'  ,
        'pdf'  ,
        'png'  ,
        'ppt'  ,
        'pptx' ,
        'pub'  ,
        'rtf'  ,
        'shtml',
        'tar'  ,
        'tif'  ,
        'tiff' ,
        'txt'  ,
        'xls'  ,
        'xlsx' ,
        'xml'  ,
        'zip'  ,
    ];


    /**
     * Check if file extension is allowed.
     *
     * @param $filename
     * @return bool
     */
    public static function isAllowed($filename)
    {
        $ext = trim(pathinfo($filename, PATHINFO_EXTENSION));

        return in_array($ext, static::ALLOWED_EXTENSIONS);
    }

}