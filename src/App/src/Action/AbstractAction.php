<?php
namespace App\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

class AbstractAction implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $method = strtolower($request->getMethod());
        
        try {
            return $this->$method($request);
        } catch (\Exception $e) {
            return $this->response([
                'erro' => $e->getMessage()
            ], $e->getCode());
        } catch (\Throwable $e) {
            return $this->response([
                'erro' => $e->getMessage()
            ], 500);
        }
    }
    
    protected function response($response, $code = 200) {
        return new JsonResponse($response, $code);
    }
}
