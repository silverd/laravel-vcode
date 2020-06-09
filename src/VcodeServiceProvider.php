<?php

namespace Cann\Vcode;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Cann\Vcode\Business\VcodeBusiness;

class VcodeServiceProvider extends BaseServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes.php');

        $this->registerPublishing();

        $this->registerValidator();
    }

    public function register()
    {
        // do nothing
    }

    protected function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/../config' => config_path()], 'vcode');
            $this->publishes([__DIR__ . '/../database/migrations' => database_path('migrations')], 'vcode');
        }
    }

    protected function registerValidator()
    {
        // 验证中文手机号
        \Validator::extend('zh_mobile', function ($attribute, $value) {
            return preg_match('/^(\+?0?86\-?)?1[3-9]{1}\d{9}$/', $value);
        }, '你输入的是一个无效的手机号码');

        // 验证短信验证码有效性
        \Validator::extend('verify_vcode', function ($attribute, $value) {
            return VcodeBusiness::verifyVcode(request('vcode_key'), $value);
        }, '验证失败');
    }
}