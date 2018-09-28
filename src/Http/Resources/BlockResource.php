<?php

namespace Pvtl\VoyagerPageBlocks\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BlockResource
{
    protected $data = [];
    public function __construct($data)
    {
        $this->data = $data;
        return $this->toArray();
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }
}
