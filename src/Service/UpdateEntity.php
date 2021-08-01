<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class UpdateEntity
{

    public function update($request, $fileName)
    {
        $dataFull = $request->getContent();
        $delimite = "multipart/form-data; boundary=";
        $boundary = "--" . explode($delimite, $request->headers->get("content-type"))[1];
        $elements = str_replace([$boundary, 'Content-Disposition: form-data;', "name="], "", $dataFull);
        $elementsTab = explode("\r\n\r\n", $elements);

        $data = [];
        for ($i = 0; isset($elementsTab[$i + 1]); $i += 2) {
            $key = str_replace(["\r\n", '"', '"'], "", $elementsTab[$i]);

            //Remove spaces (or other characters) at the start and end of string
            $key = trim($key);


            if (strchr($key, $fileName)) {
                $type = explode("Content-Type: ", $key)[1];
                $ftype = explode("/", $type)[0];
                if ($ftype == 'image') {
                    $stream = fopen('php://memory', 'r+');
                    fwrite($stream, $elementsTab[$i + 1]);
                    rewind($stream);
                    $data[$fileName] =  $stream;
                } else {
                    throw new BadRequestException("L'avatar de l'utilisateur doit être une image", 400);
                }
            } else {
                $val = str_replace(["\r\n", "--"], '', $elementsTab[$i + 1]);
                $data[$key] =  $val;
            }
            // }


            //     if (strchr($key, $fileName)) {
            //         $stream = fopen('php://memory', 'r+');
            //         fwrite($stream, $elementsRow[$i + 1]);
            //         rewind($stream);
            //         $data[$fileName] = $stream;
            //     } else {
            //         $val = $elementsRow[$i + 1];
            //         $data[$key] = $val;
            //     }
        }

        return $data;
    }
}
