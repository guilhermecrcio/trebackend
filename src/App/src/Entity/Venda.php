<?php
namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    protected $table = 'vendas';
    protected $primaryKey = 'id';
    public $timestamps = null;
}