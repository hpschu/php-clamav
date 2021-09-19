<?php
declare(strict_types=1);
namespace PhpClamav;

class App
{
    public static function init() {

        $request = new Request();

        if ($request->getReqUri() == '/') {
            echo <<< HTML
            <head>
                <title>PHP-Clamav scanner</title>
            </head>
            <body>
                <h3>PHP-Clamav scanner</h3>
                <p>To scan your stuff, throw a POST to /scan with a file=@yourfile.png</p> 
                <p>Returns "OK" if the file was okay. Otherwise you'll see the result from the clamav scan.</p>
                <br>
                <p><b>Example:</b></p>
                <p>Request: curl -X POST -F file=@pic.jpg [server url]/scan</p>
                <p>Response: OK</p>
                <p>Request: curl -X POST -F file=@eicar.txt [server url]/scan</p>
                <p>Response: Win.Test.EICAR_HDB-1 FOUND</p>
                <p><b>Other endpoints:</b></p>
                <p>GET /ping returns PONG if the ClamAV backend service is running.</p>
            </body>
            HTML;

        }

        if ($request->getReqUri() === '/ping' && $request->getMethod() === 'GET') {
            $data = '';
            try {
                $address = gethostbyname('clamav');
                $client = new ClamClient($address, 3310);
                $data = $client->ping() == true ? 'PONG' : $data;
                $client->close();
            } catch (ErrorException $e) {
                header('Content-Type: application/json');
                echo json_encode(['response' => '500', 'data' => $data, 'error' => 1]);
            }
            header('Content-Type: application/json');
            echo json_encode(['response' => '200', 'data' => $data, 'error' => 0]);
        }

        if ($request->getReqUri() === '/scan' && $request->getMethod() === 'POST') {
            $data = '';
            try {
                if (!$_FILES) {
                    header('Content-Type: application/json');
                    echo json_encode(['response' => '200', 'data' => $data, 'error' => 2]);
                    exit;
                }
                $address = gethostbyname('clamav');
                $client = new ClamClient($address, 3310);
                $stream_file = $_FILES['file']['tmp_name'];
                $stream = fopen($stream_file, 'r');
                $data = $client->scanStream($stream);
                fclose($stream);
                $client->close();
            } catch (ErrorException $e) {
                header('Content-Type: application/json');
                echo json_encode(['response' => '500', 'data' => $data, 'error' => 1]);
            }
            header('Content-Type: application/json');
            echo json_encode(['response' => '200', 'data' => $data, 'error' => 0]);
        }

        http_response_code(404);
    }

}