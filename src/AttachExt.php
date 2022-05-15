<?php


namespace Juanparati\Sendinblue;


class AttachExt
{

    /**
     * Allowed extensions
     *
     * @see https://developers.sendinblue.com/reference/sendtransacemail
     */
    const ALLOWED_EXTENSIONS = [
        'aif'   ,
        'aifc'  ,
        'aiff'  ,
        'avi'   ,
        'bmp'   ,
        'cgm'   ,
        'css'   ,
        'csv'   ,
        'doc'   ,
        'docm'  ,
        'docx'  ,
        'eps'   ,
        'ez'    ,
        'flac'  ,
        'gif'   ,
        'htm'   ,
        'html'  ,
        'ics'   ,
        'jpeg'  ,
        'jpg'   ,
        'm4a'   ,
        'm4v'   ,
        'mkv'   ,
        'mobi'  ,
        'mov'   ,
        'mp3'   ,
        'mp4'   ,
        'mpeg'  ,
        'mpg'   ,
        'msg'   ,
        'ods'   ,
        'odt'   ,
        'ogg'   ,
        'pdf'   ,
        'pkpass',
        'png'   ,
        'ppt'   ,
        'pptx'  ,
        'pub'   ,
        'rtf'   ,
        'shtml' ,
        'tar'   ,
        'tif'   ,
        'tiff'  ,
        'txt'   ,
        'wav'   ,
        'wma'   ,
        'wmv'   ,
        'xls'   ,
        'xlsm'  ,
        'xlsx'  ,
        'xml'   ,
        'zip'   ,
    ];


    /**
     * Check if file extension is allowed.
     *
     * @param $filename
     * @return bool
     */
    public static function isAllowed($filename): bool
    {
        $ext = trim(pathinfo($filename, PATHINFO_EXTENSION));

        return in_array($ext, static::ALLOWED_EXTENSIONS);
    }

}
