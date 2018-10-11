<?php
namespace App\Action;

use App\Entity\Vendedor;

class VendedoresAction extends AbstractAction
{
    public function get($request) {
        $id = $request->getAttribute('id');

        if (empty($id)) {
            $ativo = $request->getAttribute('ativo');
            
            if (empty($ativo)) {
                $vendedores = Vendedor::all();
            } else {
                $vendedores = Vendedor::where('ativo', '=', (($ativo == 'true') ? true : false))->get();
            }
            
            if (empty($vendedores)) {
                throw new \Exception('Nenhum vendedor cadastrado', 404);
            }
        }  else {
            $vendedores = Vendedor::find((integer) $id);
            
            if (empty($vendedores)) {
                throw new \Exception('Vendedor não encontrado', 404);
            }
        }
        
        return $this->response($vendedores->toArray(), 200);
    }
    
    public function post($request) {
        $params = $request->getParsedBody();
        
        if (empty($params['nome']) || empty($params['email']))  {
            throw new \Exception('Os seguintes campos são obrigatórios: nome e email', 400);
        }
        
        if (!filter_var($params['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('E-mail inválido', 400);
        }
        
        $validaEmail = Vendedor::where('email', '=', (string) $params['email'])->first();
        
        if (!empty($validaEmail)) {
            throw new \Exception('E-mail já cadastrado', 400);
        }
        
        $vendedor = new Vendedor();
        $vendedor->nome = (string) $params['nome'];
        $vendedor->email = (string) $params['email'];
        
        if (array_key_exists('ativo', $params)) {
            $vendedor->ativo = (boolean) $params['ativo'];
        }
        
        $vendedor->save();
        
        $vendedor = Vendedor::find($vendedor->id);
        
        return $this->response(
            $vendedor->toArray()
        , 201);
    }
    
    public function put($request) {
        $id = $request->getAttribute('id');
        $params = $request->getParsedBody();
        
        $vendedor = Vendedor::find((integer) $id);
        
        if (empty($vendedor)) {
            throw new \Exception('Vendedor não encontrado', 404);
        }
        
        if (empty($params['nome']) || empty($params['email']))  {
            throw new \Exception('Os seguintes campos são obrigatórios: nome e email', 400);
        }
        
        $vendedor->nome = (string) $params['nome'];
        
        if (!filter_var($params['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('E-mail inválido', 400);
        }
        
        $validaEmail = Vendedor::where('email', '=', (string) $params['email'])
            ->where('id', '!=', (integer) $id)
            ->first();
    
        if (!empty($validaEmail)) {
            throw new \Exception('E-mail já cadastrado', 400);
        }
        
        $vendedor->email = (string) $params['email'];
        
        if (array_key_exists('ativo', $params)) {
            $vendedor->ativo = (boolean) $params['ativo'];
        }
        
        $vendedor->save();
        
        return $this->response(
            $vendedor->toArray()
        , 200);
    }
}