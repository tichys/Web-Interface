<?php

namespace App\Services\Server;


class ServerQuery
{
    /**
     * The server's response.
     *
     * @var array
     */
    public $response;
    /**
     * The socket to the server.
     *
     * @var
     */
    private $socket;

    /**
     * Auth Key
     * Can be supplied in setup or directly in the request
     *
     * @var String
     */
    private $auth;

    /**
     * Sets up the query object.
     *
     * @param string $address
     * @param string $port
     * @param string $auth_key
     *
     * @return void
     * @throws \Exception
     */
    public function setUp($address, $port, $auth = NULL)
    {
        if (!isset($address) || !isset($port)) {
            throw new \Exception("Invalid address or port.");
        }

        if ($auth != NULL) {
            $this->auth = $auth;
        }

        $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($sock === FALSE) {
            throw new \Exception("Error creating socket.");
        }
        if (socket_connect($sock, $address, $port) === FALSE) {
            throw new \Exception("Error connecting to host.");
        }
        $this->socket = $sock;
        return TRUE;
    }

    /**
     * Closes the socket -> Do after you finished querying the server
     */
    public function close()
    {
        socket_close($this->socket);
    }

    /**
     * Queries the server we're connected to.
     *
     * @param array   query
     * @return array
     * @throws \Exception
     */
    public function runQuery($query)
    {
        if ($this->socket == NULL) {
            throw new \Exception("Server not setup");
        }

        if (!isset($query) || !sizeof($query) || !is_array($query)) {
            throw new \Exception("Invalid query variable passed.");
        }
        $assembled_query = $this->assembleQuery($query);
        $length = strlen($assembled_query);
        while (TRUE) {
            $sent = socket_write($this->socket, $assembled_query, $length);
            if ($sent === FALSE) {
                break;
            }
            if ($sent < $length) {
                $assembled_query = substr($assembled_query, $sent);
                $length -= $sent;
            } else {
                break;
            }
        }
        $result = socket_read($this->socket, 10000, PHP_BINARY_READ);
        $this->response = $this->parseResult($result);

        return $this->response;
    }

    /**
     * Creates a query to send to the SS13 server.
     *
     * @param array $query
     * @return string
     */
    private function assembleQuery($query)
    {
        if (isset($this->auth)) {
            $query["auth"] = $this->auth;
        }

        $assembled_query = json_encode($query);
        return "\x00\x83" . pack("n", strlen($assembled_query) + 6) . "\x00\x00\x00\x00\x00" . $assembled_query . "\x00";
    }

    /**
     * Parses the response from the server.
     *
     * @param string $result
     * @return object $response
     * @throws \Exception
     */
    private function parseResult($result)
    {
        if (!isset($result)) {
            throw new \Exception ('No result specified');
        }
        if ($result[0] == "\x00" || $result[1] == "\x83") {
            $sizebytes = unpack('n', $result[2] . $result[3]);
            $size = $sizebytes[1] - 1;

            if ($result[4] == "\x06") {
                $unpackstr = "";
                $index = 5;

                while ($size > 0) {
                    $size--;
                    $unpackstr .= $result[$index];
                    $index++;
                }

                $unpackstr = str_replace("\x00", "", $unpackstr);
                $response = json_decode($unpackstr);

                return $response;
            } else if ($result[4] == "\x2a") {
                throw new \Exception('Unsupported repsonse type - Integer');
            } else {
                throw new \Exception('Unsupported repsonse type - Other');
            }
        }
        throw new \Exception('Unknown Response');
    }
}