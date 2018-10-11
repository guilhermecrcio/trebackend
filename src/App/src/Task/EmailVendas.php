<?php
require __DIR__.'/autoload.php';

use App\Entity\Venda;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$data = new \DateTime('now');
$data->sub(new \DateInterval('P1D'));

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
->whereBetween('vendas.data_venda', [
    $data->format('Y-m-d 00:00:00'),
    $data->format('Y-m-d 23:59:59')
])
->get();

if (!empty($vendas) && $vendas->count() > 0) {
    $arquivo = '/tmp/relatorio-diario-vendas-'.$data->format('Y-m-d').'.csv';
    
    if (file_exists($arquivo)) {
        unlink($arquivo);
    }
    
    $fh = fopen($arquivo, 'w');
    
    fwrite($fh, "'venda';'código do vendedor';'nome do vendedor';'email do vendedor';'valor da venda';'data da venda';'comissão %';'valor da comissão'\n");
    
    $total_venda = 0;
    $total_comissao = 0;
    $total_por_vendedor = array();
    $vendedores = array();
    
    foreach ($vendas->toArray() as $venda) {
        $venda = (object) $venda;
        
        $total_venda += $venda->valor;
        $valor_comissao = ($venda->valor / 100) * $venda->comissao;
        $total_comissao += $valor_comissao;
        
        if (!array_key_exists($venda->vendedor_id, $total_por_vendedor)) {
            $vendedores[$venda->vendedor_id] = $venda->vendedor_nome;
            
            $total_por_vendedor[$venda->vendedor_id] = array(
                'venda' => 0,
                'comissao' => 0
            );
        }
        
        $total_por_vendedor[$venda->vendedor_id]['venda'] += $venda->valor;
        $total_por_vendedor[$venda->vendedor_id]['comissao'] += $valor_comissao;
        
        $valor_comissao = number_format($valor_comissao, 2, ',', '.');
        $venda->valor = number_format($venda->valor, 2, ',', '.');
        $venda->data_venda = new \DateTime($venda->data_venda);
        $venda->data_venda = $venda->data_venda->format('Y-m-d H:i:s');
        $venda->comissao = number_format($venda->comissao, 2, ',', '.');
        
        fwrite($fh, "{$venda->id};{$venda->vendedor_id};'{$venda->vendedor_nome}';'{$venda->vendedor_email}';{$venda->valor};{$venda->data_venda};{$venda->comissao};{$valor_comissao}\n");
    }
    
    fclose($fh);
    
    $vendas = array_map(function($v) {
        return $v['venda'];
    }, $total_por_vendedor);
    
    arsort($vendas);
    
    $comissoes = array_map(function($v) {
        return $v['comissao'];
    }, $total_por_vendedor);
    
    arsort($comissoes);
    
    $li_vendas = "";
    foreach ($vendas as $vendedor => $valor_venda) {
        $vendedor_nome = $vendedores[$vendedor];
        $li_vendas .= "<li>{$vendedor_nome}: R$ ".number_format($valor_venda, 2, ',', '.')."</li>";
    }
    
    $li_comissoes = "";
    foreach ($comissoes as $vendedor => $valor_comissao) {
        $vendedor_nome = $vendedores[$vendedor];
        $li_comissoes .= "<li>{$vendedor_nome}: R$ ".number_format($valor_comissao, 2, ',', '.')."</li>";
    }
    
    $body = "
        <strong>Relatório diário de vendas ".$data->format('Y-m-d')."</strong><br />
        <hr />
        <strong>Total de vendas: R$ ".number_format($total_venda, 2, ',', '.')."</strong><br />
        <strong>Total de comissão: R$ ".number_format($total_comissao, 2, ',', '.')."</strong><br />
        <hr />
        <strong>Total de vendas por vendedor:</strong><br/>
        <ul>{$li_vendas}</ul>
        <strong>Total de comissão por vendedor:</strong><br/>
        <ul>{$li_comissoes}</ul>
        <hr />
        <strong>Relatório completo em anexo.</strong>
    ";
    
    $mail = new PHPMailer(true);
    
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'guilherme.crcio@gmail.com';
        $mail->Password = 'secret';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;     
        
        $mail->setFrom('noreply@tray.com.br', 'Tray E-commerce');
        $mail->addAddress('guilherme.crcio@gmail.com', 'Guilherme Curcio');
        $mail->addAttachment($arquivo);
        $mail->isHTML(true);
        $mail->Subject = utf8_decode('Relatório diário de vendas');
        $mail->Body = $body;
        $mail->send();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}