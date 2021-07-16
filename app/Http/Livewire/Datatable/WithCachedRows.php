<?php

namespace App\Http\Livewire\Datatable;

trait WithCachedRows
{
    protected bool $useCache = false;

    public function useCachedRows()
    {
        $this->useCache = true;
    }

    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Exception
     */
    public function cache($callback)
    {
        #use component livewire id
        $cacheKey = $this->id;
        if ($this->useCache && cache()->has($cacheKey)) {
            return cache()->get($cacheKey);
        }
        $result = $callback();
        cache()->put($cacheKey, $result,3600);
        return $result;
    }
}
