<?php

namespace Pvtl\VoyagerPageBlocks\Http\Resources;

use Pvtl\VoyagerPageBlocks\Http\Resources\BlockResource;

class CardColumnsResource extends BlockResource
{
    public function toArray()
    {
        $data = [];

        for ($col = 1; $col <= 10; $col++) {
            if (! array_get($this->data, "title_{$col}", null)) {
                break;
            }
            $data[] = [
                "type" => "CMSCard",
                "image" => publicAssetUrl($this->data["image_{$col}"]),
                "title" => $this->data["title_{$col}"],
                "link" => array_get($this->data, "link_{$col}", null),
                "subline" => $this->data["content_{$col}"]
            ];
        }
        return ["type" => "CMSWrapper", "data" => $data];
    }
}
