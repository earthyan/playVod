<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/20 0020
 * Time: 10:09
 */

namespace App\Http\Controllers\Api;


use App\Exceptions\APIException;
use App\Http\Controllers\Controller;
use App\Http\Services\VideoService;
use Illuminate\Support\Facades\Cache;

class TestController extends Controller
{
    public function test1()
    {
        $data = Cache::get(md5("CFD9C7E9A95A4AFEA004D8B37759849C:1"));

        var_dump($data);die;


        $ch = curl_init();
        $size = base_path('听妈妈的话.mp4');

        curl_setopt_array($ch, [
            CURLOPT_URL => 'http://192.168.110.233/samba/hainan/dhvod/public/index.php/api/upload/auth?' . http_build_query([
                    'title' => '听妈妈的话123456',
                    'filename' => '听妈妈的话.mp4',
                    'fileSize' => filesize($size)
                ]),
            CURLOPT_RETURNTRANSFER => 1
        ]);

        $result = curl_exec($ch);

        curl_close($ch);

        return response()->json(json_decode($result, true));
    }

    public function test2()
    {
        $uploadAddress = 'eyJFbmRwb2ludCI6Imh0dHBzOi8vb3NzLWNuLXNoYW5naGFpLmFsaXl1bmNzLmNvbSIsIkJ1Y2tldCI6ImluLTIwMTgwMzE5MTQ1ODA0MTA5LThieTFhbHQ1aGwiLCJGaWxlTmFtZSI6InZpZGVvLzFhZDA5NmVjLTE2NDFiMzU2N2ZmLTAwMDQtOWM1Mi0yNjEtNGNmNjEubXA0In0=';
        $uploadAuth = 'eyJTZWN1cml0eVRva2VuIjoiQ0FJU3pBUjFxNkZ0NUIyeWZTaklyNHYySGUzanI1ZDFoWktDVkdQa3FuZ0ZUZW9mbjY3R3RUejJJSHBOZTNocUIrMGZzUGt3bEdsVTZmZ2NsclVxRWM0YUdoT2FNSmN1djhVT29GM3dKcGZadjh1ODRZQURpNUNqUWFkVzBxMVNtSjI4V2Y3d2FmK0FVQm5HQ1RtZDVNY1lvOWJUY1RHbFFDWnVXLy90b0pWN2I5TVJjeENsWkQ1ZGZybC9MUmRqcjhsbzF4R3pVUEcyS1V6U24zYjNCa2hsc1JZZTcyUms4dmFIeGRhQXpSRGNnVmJtcUpjU3ZKK2pDNEM4WXM5Z0c1MTlYdHlwdm9weGJiR1Q4Q05aNXo5QTlxcDlrTTQ5L2l6YzdQNlFIMzViNFJpTkw4L1o3dFFOWHdoaWZmb2JIYTlZcmZIZ21OaGx2dkRTajQzdDF5dFZPZVpjWDBha1E1dTdrdTdaSFArb0x0OGphWXZqUDNQRTNyTHBNWUx1NFQ0OFpYVVNPRHREWWNaRFVIaHJFazRSVWpYZEk2T2Y4VXJXU1FDN1dzcjIxN290ZzdGeXlrM3M4TWFIQWtXTFg3U0IyRHdFQjRjNGFFb2tWVzRSeG5lelc2VUJhUkJwYmxkN0JxNmNWNWxPZEJSWm9LK0t6UXJKVFg5RXoycExtdUQ2ZS9MT3M3b0RWSjM3V1p0S3l1aDRZNDlkNFU4clZFalBRcWl5a1QwdEZncGZUSzFSemJQbU5MS205YmFCMjUvelcrUGREZTBkc1Znb0xGS0twaUdXRzNSTE5uK3p0Sjl4YUZ6ZG9aeUluUFNYcXNJNVRGWit2b3dHVlZEQUlkeDg5bFZpLy9heTYxT044ZVB1VlRmbzNCSmhxNFNFcGRFU3N4UThJcWY5MzdiRGhGT0U0aXpNTzV0ZXNkek1SV2hpVFM2d2YzRkUyLzJJamhvRjNVdGJ5ajdsWVVoQ3Nnck1pamJwSUpKRmpPYjM3M2RGRTdwVnArUFVjRDZwNVY1OEV1aU81N3NidWovVzQyV01ocDBhZ0FFRTNMdG1Od0dlUGlpY3BQYU00S1Zodlc0d2JhQU9TZUpodVpQcnJ2bWdaVUxWQzlSYWFsTmNwNEFyekgvYWZsVlIzSkRTTDMzaHg4dTlLSEpoaHJPRDQ3Tld5cEVMaUtZamtGM1NLY0hrTG5UczFOL3gvbjc5STZtTVl6aHBWdWpMZnprRlpUNzdEOStxUXhXWVZzbmNHcGszaGtySHJrS05QeVNvL0x4dVdBPT0iLCJBY2Nlc3NLZXlJZCI6IlNUUy5OSENWV1dCSFQyUGlWRVVOeFBBZTNwa21XIiwiQWNjZXNzS2V5U2VjcmV0IjoiSEFvUXRrWWM5UTg3NlVxNkx6MmVVMUNpRm5ka2c4UUc3ekt4aW1XcXNXN3QiLCJFeHBpcmF0aW9uIjoiMzYwMCJ9';

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => 'http://192.168.110.233/samba/hainan/dhvod/public/index.php/api/upload/initMulti?' . http_build_query([
                    'UploadAddress' => $uploadAddress,
                    'UploadAuth' => $uploadAuth,
                ]),
            CURLOPT_RETURNTRANSFER => 1,
        ]);

        $result = curl_exec($ch);
        curl_close($ch);

        return response()->json(json_decode($result, true));
    }

    public function test3()
    {
        $uploadAddress = 'eyJFbmRwb2ludCI6Imh0dHBzOi8vb3NzLWNuLXNoYW5naGFpLmFsaXl1bmNzLmNvbSIsIkJ1Y2tldCI6ImluLTIwMTgwMzE5MTQ1ODA0MTA5LThieTFhbHQ1aGwiLCJGaWxlTmFtZSI6InZpZGVvLzFhZDA5NmVjLTE2NDFiMzU2N2ZmLTAwMDQtOWM1Mi0yNjEtNGNmNjEubXA0In0=';
        $uploadAuth = 'eyJTZWN1cml0eVRva2VuIjoiQ0FJU3pBUjFxNkZ0NUIyeWZTaklyNHYySGUzanI1ZDFoWktDVkdQa3FuZ0ZUZW9mbjY3R3RUejJJSHBOZTNocUIrMGZzUGt3bEdsVTZmZ2NsclVxRWM0YUdoT2FNSmN1djhVT29GM3dKcGZadjh1ODRZQURpNUNqUWFkVzBxMVNtSjI4V2Y3d2FmK0FVQm5HQ1RtZDVNY1lvOWJUY1RHbFFDWnVXLy90b0pWN2I5TVJjeENsWkQ1ZGZybC9MUmRqcjhsbzF4R3pVUEcyS1V6U24zYjNCa2hsc1JZZTcyUms4dmFIeGRhQXpSRGNnVmJtcUpjU3ZKK2pDNEM4WXM5Z0c1MTlYdHlwdm9weGJiR1Q4Q05aNXo5QTlxcDlrTTQ5L2l6YzdQNlFIMzViNFJpTkw4L1o3dFFOWHdoaWZmb2JIYTlZcmZIZ21OaGx2dkRTajQzdDF5dFZPZVpjWDBha1E1dTdrdTdaSFArb0x0OGphWXZqUDNQRTNyTHBNWUx1NFQ0OFpYVVNPRHREWWNaRFVIaHJFazRSVWpYZEk2T2Y4VXJXU1FDN1dzcjIxN290ZzdGeXlrM3M4TWFIQWtXTFg3U0IyRHdFQjRjNGFFb2tWVzRSeG5lelc2VUJhUkJwYmxkN0JxNmNWNWxPZEJSWm9LK0t6UXJKVFg5RXoycExtdUQ2ZS9MT3M3b0RWSjM3V1p0S3l1aDRZNDlkNFU4clZFalBRcWl5a1QwdEZncGZUSzFSemJQbU5MS205YmFCMjUvelcrUGREZTBkc1Znb0xGS0twaUdXRzNSTE5uK3p0Sjl4YUZ6ZG9aeUluUFNYcXNJNVRGWit2b3dHVlZEQUlkeDg5bFZpLy9heTYxT044ZVB1VlRmbzNCSmhxNFNFcGRFU3N4UThJcWY5MzdiRGhGT0U0aXpNTzV0ZXNkek1SV2hpVFM2d2YzRkUyLzJJamhvRjNVdGJ5ajdsWVVoQ3Nnck1pamJwSUpKRmpPYjM3M2RGRTdwVnArUFVjRDZwNVY1OEV1aU81N3NidWovVzQyV01ocDBhZ0FFRTNMdG1Od0dlUGlpY3BQYU00S1Zodlc0d2JhQU9TZUpodVpQcnJ2bWdaVUxWQzlSYWFsTmNwNEFyekgvYWZsVlIzSkRTTDMzaHg4dTlLSEpoaHJPRDQ3Tld5cEVMaUtZamtGM1NLY0hrTG5UczFOL3gvbjc5STZtTVl6aHBWdWpMZnprRlpUNzdEOStxUXhXWVZzbmNHcGszaGtySHJrS05QeVNvL0x4dVdBPT0iLCJBY2Nlc3NLZXlJZCI6IlNUUy5OSENWV1dCSFQyUGlWRVVOeFBBZTNwa21XIiwiQWNjZXNzS2V5U2VjcmV0IjoiSEFvUXRrWWM5UTg3NlVxNkx6MmVVMUNpRm5ka2c4UUc3ekt4aW1XcXNXN3QiLCJFeHBpcmF0aW9uIjoiMzYwMCJ9';

        $uploadId = '65B33CFF45164B6296F1F8BEF695EB6A';
        $filename = '听妈妈的话.mp4';
        $filePath = base_path($filename);

        $fp = fopen($filePath, 'rb');

        $partSize = 5242880;

        $totalPart = filesize($filePath) % $partSize == 0 ? filesize($filePath) / $partSize : (intval(filesize($filePath) / $partSize) + 1);

        $remind = filesize($filePath);

        $results = [];

        for ($i = 0; $i < $totalPart; $i++) {
            if ($i == 0) {
                continue;
            }
            $size = $partSize > $remind ? $remind : $partSize;
            $remind -= $size;
            $content = fread($fp, $size);
            $ch = curl_init();

            curl_setopt_array($ch, [
                CURLOPT_URL => 'http://192.168.110.233/samba/hainan/dhvod/public/index.php/api/upload/part?' . http_build_query([
                        'uploadId' => $uploadId,
                        'UploadAddress' => $uploadAddress,
                        'UploadAuth' => $uploadAuth,
                        'filename' => $filename,
                        'size' => $size,
                        'partNum' => $i + 1
                    ]),
                CURLOPT_HTTPHEADER => ['Content-Type: application/octet-stream'],
                CURLOPT_POST => 1,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_POSTFIELDS => $content,
            ]);

            $result = curl_exec($ch);
            curl_close($ch);

            $result = json_decode($result, true);

            $results[] = [
                'PartNumber' => $i + 1,
                'ETag' => $result['data']
            ];
        }

        echo json_encode($results);
    }

    public function test4()
    {
        $uploadAddress = 'eyJFbmRwb2ludCI6Imh0dHBzOi8vb3NzLWNuLXNoYW5naGFpLmFsaXl1bmNzLmNvbSIsIkJ1Y2tldCI6ImluLTIwMTgwMzE5MTQ1ODA0MTA5LThieTFhbHQ1aGwiLCJGaWxlTmFtZSI6InZpZGVvLzFhZDA5NmVjLTE2NDFiMzU2N2ZmLTAwMDQtOWM1Mi0yNjEtNGNmNjEubXA0In0=';
        $uploadAuth = 'eyJTZWN1cml0eVRva2VuIjoiQ0FJU3pBUjFxNkZ0NUIyeWZTaklyNHYySGUzanI1ZDFoWktDVkdQa3FuZ0ZUZW9mbjY3R3RUejJJSHBOZTNocUIrMGZzUGt3bEdsVTZmZ2NsclVxRWM0YUdoT2FNSmN1djhVT29GM3dKcGZadjh1ODRZQURpNUNqUWFkVzBxMVNtSjI4V2Y3d2FmK0FVQm5HQ1RtZDVNY1lvOWJUY1RHbFFDWnVXLy90b0pWN2I5TVJjeENsWkQ1ZGZybC9MUmRqcjhsbzF4R3pVUEcyS1V6U24zYjNCa2hsc1JZZTcyUms4dmFIeGRhQXpSRGNnVmJtcUpjU3ZKK2pDNEM4WXM5Z0c1MTlYdHlwdm9weGJiR1Q4Q05aNXo5QTlxcDlrTTQ5L2l6YzdQNlFIMzViNFJpTkw4L1o3dFFOWHdoaWZmb2JIYTlZcmZIZ21OaGx2dkRTajQzdDF5dFZPZVpjWDBha1E1dTdrdTdaSFArb0x0OGphWXZqUDNQRTNyTHBNWUx1NFQ0OFpYVVNPRHREWWNaRFVIaHJFazRSVWpYZEk2T2Y4VXJXU1FDN1dzcjIxN290ZzdGeXlrM3M4TWFIQWtXTFg3U0IyRHdFQjRjNGFFb2tWVzRSeG5lelc2VUJhUkJwYmxkN0JxNmNWNWxPZEJSWm9LK0t6UXJKVFg5RXoycExtdUQ2ZS9MT3M3b0RWSjM3V1p0S3l1aDRZNDlkNFU4clZFalBRcWl5a1QwdEZncGZUSzFSemJQbU5MS205YmFCMjUvelcrUGREZTBkc1Znb0xGS0twaUdXRzNSTE5uK3p0Sjl4YUZ6ZG9aeUluUFNYcXNJNVRGWit2b3dHVlZEQUlkeDg5bFZpLy9heTYxT044ZVB1VlRmbzNCSmhxNFNFcGRFU3N4UThJcWY5MzdiRGhGT0U0aXpNTzV0ZXNkek1SV2hpVFM2d2YzRkUyLzJJamhvRjNVdGJ5ajdsWVVoQ3Nnck1pamJwSUpKRmpPYjM3M2RGRTdwVnArUFVjRDZwNVY1OEV1aU81N3NidWovVzQyV01ocDBhZ0FFRTNMdG1Od0dlUGlpY3BQYU00S1Zodlc0d2JhQU9TZUpodVpQcnJ2bWdaVUxWQzlSYWFsTmNwNEFyekgvYWZsVlIzSkRTTDMzaHg4dTlLSEpoaHJPRDQ3Tld5cEVMaUtZamtGM1NLY0hrTG5UczFOL3gvbjc5STZtTVl6aHBWdWpMZnprRlpUNzdEOStxUXhXWVZzbmNHcGszaGtySHJrS05QeVNvL0x4dVdBPT0iLCJBY2Nlc3NLZXlJZCI6IlNUUy5OSENWV1dCSFQyUGlWRVVOeFBBZTNwa21XIiwiQWNjZXNzS2V5U2VjcmV0IjoiSEFvUXRrWWM5UTg3NlVxNkx6MmVVMUNpRm5ka2c4UUc3ekt4aW1XcXNXN3QiLCJFeHBpcmF0aW9uIjoiMzYwMCJ9';

        $uploadId = '65B33CFF45164B6296F1F8BEF695EB6A';

        $parts = '[{"PartNumber":2,"ETag":"\"3BF43E40F7792D760D320545F51FE33F\""},{"PartNumber":3,"ETag":"\"3DC4CBE030E7778B14D9C94BE3E40C88\""},{"PartNumber":4,"ETag":"\"B4F12BB8B54688902E131EC396429393\""},{"PartNumber":5,"ETag":"\"BBE3BA9374F55F9B0AA8B2E4E44CF6E1\""},{"PartNumber":6,"ETag":"\"8F6EEE190753200A5F6831C0DF496904\""},{"PartNumber":7,"ETag":"\"DEAEF74535A56B8EA6B5E79A32091817\""}]';

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => 'http://192.168.110.233/samba/hainan/dhvod/public/index.php/api/upload/complete?' . http_build_query([
                    'uploadId' => $uploadId,
                    'UploadAddress' => $uploadAddress,
                    'UploadAuth' => $uploadAuth,
                    'parts' => $parts,
                ]),
            CURLOPT_RETURNTRANSFER => 1,
        ]);

        $result = curl_exec($ch);
        curl_close($ch);

        print_r($result) ;
    }
}