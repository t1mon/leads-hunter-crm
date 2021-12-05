<?php

namespace App\Journal\Facade;

use Illuminate\Support\Facades\Facade;

class JournalFacade extends Facade{
    protected static function getFacadeAccessor(){
        return 'journal';
    }

}

?>