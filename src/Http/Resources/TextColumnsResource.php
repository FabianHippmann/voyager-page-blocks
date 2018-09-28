<?php

namespace Pvtl\VoyagerPageBlocks\Http\Resources;

use Pvtl\VoyagerPageBlocks\Http\Resources\BlockResource;

class TextColumnsResource extends BlockResource
{
    public function toArray()
    {
        $data = [];

        for ($col = 1; $col <= 10; $col++) {
            if (! array_get($this->data, "html_content_{$col}", null)) {
                break;
            }
            $data[] = [
                 "type" => "CMSContent",
                 "text" => $this->data["html_content_{$col}"],
            ];
        }
        return ["type" => "CMSWrapper", "data" => $data];
    }
}
