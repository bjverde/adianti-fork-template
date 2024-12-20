<?php
/**
 * Document uploader listener
 *
 * @version    8.0
 * @package    service
 * @author     Nataniel Rabaioli
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006-2014 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemDocumentUploaderService
{
    public static function getFontAwesomeIcon($file_path)
    {
        $content_type_list = array();
        $content_type_list['text/plain']  = 'far fa-file-alt';
        $content_type_list['text/html'] = 'far fa-file-code';
        $content_type_list['text/csv']  = 'fas fa-file-csv';
        $content_type_list['application/pdf']  = 'far fa-file-pdf';
        $content_type_list['application/zip']  = 'far fa-file-archive';
        $content_type_list['application/x-bzip']  = 'far fa-file-archive';
        $content_type_list['application/x-bzip2'] = 'far fa-file-archive';
        $content_type_list['application/x-tar'] = 'far fa-file-archive';
        $content_type_list['application/x-rar-compressed']  = 'far fa-file-archive';
        $content_type_list['application/rtf']  = 'far fa-file-word';
        $content_type_list['application/csv']  = 'fas fa-file-csv';
        $content_type_list['application/msword']  = 'far fa-file-word';
        $content_type_list['application/vnd.openxmlformats-officedocument.wordprocessingml.document'] = 'far fa-file-word';
        $content_type_list['application/vnd.ms-excel']  = 'far fa-file-excel';
        $content_type_list['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'] = 'far fa-file-excel';
        $content_type_list['application/vnd.ms-powerpoint']  = 'far fa-file-powerpoint';
        $content_type_list['application/vnd.openxmlformats-officedocument.presentationml.presentation'] = 'far fa-file-powerpoint';
        $content_type_list['application/vnd.oasis.opendocument.text']  = 'far fa-file-word';
        $content_type_list['application/vnd.oasis.opendocument.spreadsheet']  = 'far fa-file-word';
        $content_type_list['image/jpeg'] = 'far fa-file-image';
        $content_type_list['image/jpg'] = 'far fa-file-image';
        $content_type_list['image/png'] = 'far fa-file-image';
        $content_type_list['image/gif'] = 'far fa-file-image';
        $content_type_list['image/svg+xml'] = 'far fa-file-code';
        $content_type_list['application/xml'] = 'far fa-file-code';

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $icon = $content_type_list[$finfo->file($file_path)] ?? 'far fa-file';
        
        return $icon;
    }
    
    /**
     *
     */
    public function show()
    {
        $content_type_list = array();
        $content_type_list['txt']  = 'text/plain';
        $content_type_list['html'] = 'text/html';
        $content_type_list['csv']  = 'text/csv';
        $content_type_list['pdf']  = 'application/pdf';
        $content_type_list['rtf']  = 'application/rtf';
        $content_type_list['doc']  = 'application/msword';
        $content_type_list['docx'] = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
        $content_type_list['xls']  = 'application/vnd.ms-excel';
        $content_type_list['xlsx'] = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        $content_type_list['ppt']  = 'application/vnd.ms-powerpoint';
        $content_type_list['pptx'] = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
        $content_type_list['odt']  = 'application/vnd.oasis.opendocument.text';
        $content_type_list['ods']  = 'application/vnd.oasis.opendocument.spreadsheet';
        $content_type_list['jpeg'] = 'image/jpeg';
        $content_type_list['jpg']  = 'image/jpeg';
        $content_type_list['png']  = 'image/png';
        $content_type_list['gif']  = 'image/gif';
        $content_type_list['svg']  = 'image/svg+xml';
        $content_type_list['xml']  = 'application/xml';
        $content_type_list['zip']  = 'application/zip';
        $content_type_list['rar']  = 'application/x-rar-compressed';
        $content_type_list['bz']   = 'application/x-bzip';
        $content_type_list['bz2']  = 'application/x-bzip2';
        $content_type_list['tar']  = 'application/x-tar';
        
        $block_extensions = ['php', 'php3', 'php4', 'phtml', 'pl', 'py', 'jsp', 'asp', 'htm', 'shtml', 'sh', 'cgi', 'htaccess'];
        
        $folder = 'tmp/';
        $response = array();
        if (isset($_FILES['fileName']))
        {
            $file = $_FILES['fileName'];
            if( $file['error'] === 0 && $file['size'] > 0 )
            {
                $path = $folder.$file['name'];
                
                // check blocked file extension, not using finfo because file.php.2 problem
                foreach ($block_extensions as $block_extension)
                {
                    if (strpos(strtolower($file['name']), ".{$block_extension}") !== false)
                    {
                        $response = array();
                        $response['type'] = 'error';
                        $response['msg']  = AdiantiCoreTranslator::translate('Extension not allowed');
                        echo json_encode($response);
                        return;
                    }
                }
                
                // check file extension
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                if (!in_array($finfo->file($file['tmp_name']), array_values($content_type_list)))
                {
                    $response = array();
                    $response['type'] = 'error';
                    $response['msg'] = AdiantiCoreTranslator::translate('Extension not allowed');
                    echo json_encode($response);
                    return;
                }
                
                if (is_writable($folder) )
                {
                    if( move_uploaded_file( $file['tmp_name'], $path ) )
                    {
                        $response['type'] = 'success';
                        $response['fileName'] = $file['name'];
                    }
                    else
                    {
                        $response['type'] = 'error';
                        $response['msg'] = '';
                    }
                }
                else
                {
                    $response['type'] = 'error';
                    $response['msg'] = "Permission denied: {$path}";
                }
                echo json_encode($response);
            }
        }
    }
}
