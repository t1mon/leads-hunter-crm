<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('leads:first_simbol', function(){
    $start = now();
    $this->comment('Processing');

    $leads = \App\Models\Leads::all();

    $leads->each(function ($lead) {
        $_phone =$lead->phone.'';
        if ($_phone[0] == 8){
            $_phone[0] =  preg_replace('/^./','7', $_phone);
            $lead->update(['phone' => $_phone]);
            $this->comment("id #{$lead->id} замена первого символа на (цыфру 7) {$_phone}" );
        }

    });

    $time = $start->diffInMilliseconds(now());
    $this->comment("Processed in $time milliseconds");
});

Artisan::command('leads:duble_phone_entries', function(){
    $start = now();
    $this->comment('Processing');


    $leads = \App\Models\Leads::where('entries', '=', 1)->get();
    $phonesDuble = [];

    $this->comment("Leads count {$leads->count()}");

    foreach ($leads as $lead){
        array_push($phonesDuble,$lead->phone);
    }
    $collectionPhones = collect($phonesDuble);

    foreach ($collectionPhones->duplicates() as $phone_delete){
       $phone = \App\Models\Leads::where('phone', $phone_delete)->where('entries', '=', 1)->first();
       $phone->delete();
       $this->comment("Phone id #{$phone->id} is deleted");
    }



    $time = $start->diffInMilliseconds(now());
    $this->comment("Processed in $time milliseconds");
});
