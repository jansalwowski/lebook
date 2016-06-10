<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Support\Facades\Response;

class ApiController extends Controller
{

    /**
     * @var int
     */
    protected $statusCode = 200;

    /**
     * Helper method for Responses with Errors
     *
     * @param $message
     * @return mixed
     */
    protected function respondWithError($message)
    {
        return $this->respond(
            [
                'error' => [
                    'message' => $message,
                    'statusCode' => $this->getStatusCode()
                ]
            ]
        );
    }

    /**
     * Send Response with Data and Status Code
     *
     * @param array $data    Data for the Response
     * @param array $headers set Response headers
     * @return Response Send JSON Response to the client
     */
    protected function respond($data, $headers = [])
    {
        return Response::json($data, $this->getStatusCode(), $headers);
    }

    /**
     * Get status code of Response
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Set status code for Response
     *
     * @param int $statusCode Web status code f.e. 200, 404, 500...
     * @return $this
     */
    protected function setStatusCode($statusCode)
    {
        if (is_numeric($statusCode)) {
            $this->statusCode = $statusCode;
        }

        return $this;
    }

    /**
     * Respond if content was not found
     *
     * @param string $message
     * @return mixed
     */
    public function respondNotFound($message = "Not Found")
    {
        return $this->setStatusCode(404)->respondWithError($message);
    }

    /**
     * Respond if internal error occured
     *
     * @param string $message
     * @return mixed
     */
    public function respondInternalError($message = "Internal Error")
    {
        return $this->setStatusCode(500)->respondWithError($message);
    }
}
