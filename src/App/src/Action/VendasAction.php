<?php
namespace App\Action;

use App\Entity\Vendedor;
use App\Entity\Venda;

class VendasAction extends AbstractAction
{
    public function get($request) {
        $pagina = (integer) $request->getAttribute('pagina');
        $i = 10;
        
        if (!strlen($pagina) || $pagina < 0) {
            $pagina = 0;
        }
        
        if ($pagina != 0) {
            --$pagina;
        }
        
        $vendedor = (integer) $request->getAttribute('vendedor');
        
        if (!empty($vendedor)) {
            $vendedor = Vendedor::find($vendedor);
            
            if (empty($vendedor)) {
                throw new \Exception('Vendedor inválido', 400);
            }
                
            $vendas = Venda::select(
                'vendas.id',
                'vendas.vendedor_id',
                'vendedores.nome AS vendedor_nome',
                'vendedores.email AS vendedor_email',
                'vendas.valor',
                'vendas.data_venda',
                'vendas.comissao'
            )
            ->join('vendedores', 'vendedores.id', '=', 'vendas.vendedor_id')
            ->where('vendas.vendedor_id', '=', $vendedor->id)
            ->skip($i * $pagina)
            ->take($i)
            ->orderBy('vendas.data_venda', 'DESC')
            ->get();
        } else {
            $vendas = Venda::select(
                'vendas.id',
                'vendas.vendedor_id',
                'vendedores.nome AS vendedor_nome',
                'vendedores.email AS vendedor_email',
                'vendas.valor',
                'vendas.data_venda',
                'vendas.comissao'
            )
            ->join('vendedores', 'vendedores.id', '=', 'vendas.vendedor_id')
            ->skip($i * $pagina)
            ->take($i)
            ->orderBy('vendas.data_venda', 'DESC')
            ->get();
        }
        
        if (empty($vendas) || $vendas->count() == 0) {
            throw new \Exception('Nenhuma venda cadastrada', 404);
        }
        
        if (!empty($vendedor)) {
            $total = Venda::where('vendas.vendedor_id', '=', $vendedor->id)->count();
        } else {
            $total = Venda::count();
        }
        
        $result = [
            'vendas' => $vendas->toArray(),
            'offset' => ($i * $pagina),
            'limit' => $i,
            'total' => $total
        ];
        
        return $this->response($result, 200);
    }
    
    public function post($request) {
        $params = $request->getParsedBody();
        
        if (empty($params['vendedor']) || empty($params['valor']))  {
            throw new \Exception('Os seguintes campos são obrigatórios: vendedor e valor', 400);
        }
        
        $vendedor = Vendedor::find((integer) $params['vendedor']);
        
        if (empty($vendedor)) {
            throw new \Exception('Vendedor inválido', 400);
        }
        
        if ($vendedor->ativo == false) {
            throw new \Exception('Vendedor inativo', 400);
        }
        
        $venda = new Venda();
        $venda->vendedor_id = $vendedor->id;
        $venda->valor = (double) $params['valor'];
        $venda->comissao = $vendedor->comissao;
        $venda->save();
        
        $venda = Venda::find($venda->id);
        
        return $this->response(
            $venda->toArray()
        , 201);
    }
    
    public function delete($request) {
        $id = $request->getAttribute('id');
        
        $venda = Venda::find((integer) $id);
        
        if (empty($venda)) {
            throw new \Exception('Venda não encontrada', 404);
        }
        
        $venda->delete();
        
        return $this->response([
            'deletado' => true
        ], 200);
    }
}