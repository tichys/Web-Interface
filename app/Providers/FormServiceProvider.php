<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class FormServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        \Form::component('bsText','components.form.text',['name','value' => null,'attributes' => []]);
        \Form::component('bsTextArea','components.form.textarea',['name','value' => null,'attributes' => []]);
        \Form::component('bsSelectList','components.form.selectlist',['name','value' => null,'attributes' => []]);
        \Form::component('bsDate','components.form.date',['name','value' => null,'attributes' => []]);
        \Form::component('bsCheckbox','components.form.checkbox',['name','value' => null,'attributes' => []]);
        \Form::component('bsFile','components.form.file',['name','title' => null,'attributes' => []]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
