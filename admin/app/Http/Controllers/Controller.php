<?php

namespace App\Http\Controllers;

use App\Model\Repository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $product;
    public $user;
    public $priorities;
    public $mproxy;

    public function __construct()
    {
        $this->product = Config::get('app.name');
        $this->user = Auth::user();
        $this->priorities = ['Admin', 'Supervisor'];
        $this->mproxy = new Repository();
    }

    public function prepareMessage(bool $success, $msg){
        $msgTemplate = "<div class='row'> <div class='col-md-12 alert alert-success alert-dismissible' role='alert'><a href='#' class='close' data-dismiss='alert' aria-label='close'>×</a><center>$msg</center></div></div>";
        $errTemplate = "<div class='row'> <div class='col-md-12 alert alert-warning alert-dismissible' role='alert'><a href='#' class='close' data-dismiss='alert' aria-label='close'>×</a><center>$msg</center></div></div>";
        return $success ? $msgTemplate : $errTemplate;
    }

}
