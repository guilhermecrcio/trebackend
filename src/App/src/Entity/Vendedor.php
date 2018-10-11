<?php
namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
    protected $table = 'vendedores';
    protected $primaryKey = 'id';
    public $timestamps = null;
}