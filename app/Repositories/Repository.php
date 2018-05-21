<?php

namespace App\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class Repository
{
    protected $data;
    protected $dataFile = "data/data.json";

    public function __construct()
    {
        $this->fetch();
    }

    /**
     * Fetch data from disk
     */
    protected function fetch()
    {
        $data = Storage::get($this->dataFile);
        $this->data = json_decode($data);
    }

    /**
     * Persist data to disk
     *
     * @param $data
     */
    protected function persist($data)
    {
        $json = json_encode($data, true);
        return Storage::put($this->dataFile, $json);
    }

    /**
     * Paginate items
     *
     * @param       $items
     * @param int   $perPage
     * @param null  $page
     * @param array $options
     * @return LengthAwarePaginator
     */
    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
