<?php
namespace Phpyk\Utils;

use Gregwar\Captcha\CaptchaBuilder as Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Captcha
{

    const PHRASE_EXPIRE_SECONDS = 600; // 10 mins
    const CACHE_EXPIRE_MINUTES = 60; // 1 hour
    const PHRASE_PREFIX_STRING = 'captcha-'; //

    protected $builder;

    protected $cacheKey;

    protected $cacheDriver;

    protected $errors = [];

    protected $prefix;

    public function __construct($cacheKey = null, $prefix = null)
    {
        $this->builder = new Builder();
        $this->cacheKey = $cacheKey;
        $this->cacheDriver = Cache::store('memcached');

        if($prefix == null) {
            $this->prefix = self::PHRASE_PREFIX_STRING;
        } else {
            $this->prefix = $prefix;
        }
    }

    public function generate()
    {
        $this->builder->build();
        $this->cacheKey = CommonString::uniqueKey($this->prefix);

        // 验证码，10分钟后过期，key保留1小时
        $this->cacheDriver->put(
            $this->cacheKey,
            $this->builder->getPhrase()."#".strtotime(self::PHRASE_EXPIRE_SECONDS." seconds"),
            self::CACHE_EXPIRE_MINUTES
        );
    }

    public function getBase64Img()
    {
        return $this->builder->inline();
    }

    public function getCacheKey()
    {
        return $this->cacheKey;
    }

    public function destory()
    {
        $this->cacheDriver->forget($this->cacheKey);
    }

    public function check($value)
    {
        if($this->cacheKey == null) {
            array_push($this->errors, 'invalid captcha');
            return false;
        }

        if(!$this->cacheDriver->has($this->cacheKey)) {
            array_push($this->errors, 'invalid captcha');
            return false;
        }

        $cacheArray = preg_split("/#/", Cache::store('memcached')->get($this->cacheKey));
        $cacheExpiredAt = $cacheArray[1];
        $cacheValue = $cacheArray[0];

        if($cacheValue != Str::lower($value)) {
            array_push($this->errors, 'invalid captcha');
            return false;
        }

        if($cacheExpiredAt < time()) {
            array_push($this->errors, 'captcha is expired');
            return false;
        }

        return true;
    }

    public function getFirstError()
    {
        return $this->errors[0];
    }

    public function isValid()
    {
        return count($this->errors) == 0;
    }

    public static function getByKey($key)
    {
//        return [$value, $expiredAt];
        return preg_split("/#/", Cache::store('memcached')->get($key));
    }
}