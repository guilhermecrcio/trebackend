<?php
namespace App\Action;

use App\Entity\Venda;

class DashboardAction extends AbstractAction
{
    public function get($request) {
        $data = $request->getAttribute('data');
        
        if (!strtotime($data)) {
            throw new \Exception('Data inválida', 400);
        }
        
        $vendas = Venda::getConnectionResolver()->connection()->select("
            SELECT 
                x.vendedor_id,
                x.vendedor_nome,
                COUNT(x.id) AS qtde_vendas,
                SUM(x.valor) AS valor_vendas,
                SUM(x.valor_comissao) AS valor_comissao
            FROM (
                SELECT 
                    vendas.id,
                    vendas.vendedor_id, 
                    vendedores.nome AS vendedor_nome,
                    vendas.valor,
                    (vendas.valor / 100) * vendas.comissao AS valor_comissao
                FROM vendas
                INNER JOIN vendedores ON vendedores.id = vendas.vendedor_id
                WHERE vendas.data_venda::date = '{$data}'
            ) x
            GROUP BY x.vendedor_id, x.vendedor_nome
        ");
        
        if (empty($vendas)) {
            throw new \Exception('Nenhuma venda cadastrada', 404);
        }
        
        $vendas = array_map(function($v) {
            return (array) $v;
        }, (array) $vendas);
        
        return $this->response($vendas, 200);
    }
}