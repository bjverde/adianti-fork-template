<?php
/**
 * Document downloader service
 *
 * @version    8.0
 * @package    service
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006-2014 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemDocumentDownloaderService
{
    public static function download($file)
    {
        $content_type_list = [];
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
        
        if (is_file($file))
        {
            $info      = pathinfo($file);
            $extension = $info['extension'];
            $basename  = basename($file);
            $filesize  = filesize($file); // get the filesize
            
            header("Pragma: public");
            header("Expires: 0"); // set expiration time
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header('Content-Description: File Transfer');
            if (!empty($content_type_list[strtolower($extension)]))
            {
                header("Content-type: " . $content_type_list[strtolower($extension)] );
            }
            
            header("Content-Length: {$filesize}");
            header("Content-disposition: attachment; filename=\"{$basename}\"");
            header("Content-Transfer-Encoding: binary");
            
            flush();
            
            // a readfile da problemas no internet explorer
            // melhor jogar direto o conteudo do arquivo na tela
            readfile($file);
        }
    }
}