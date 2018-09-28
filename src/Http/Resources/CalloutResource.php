<?php

namespace Pvtl\VoyagerPageBlocks\Http\Resources;

use Pvtl\VoyagerPageBlocks\Http\Resources\BlockResource;

class CalloutResource extends BlockResource
{
    public function toArray()
    {
        $data = [
            'title' => array_get($this->data, 'title'),
            'backgroundAsset' => array_get($this->data, 'background_image') ? publicAssetUrl(array_get($this->data, 'background_image')) : null,
            'subline' => array_get($this->data, 'subline'),
            'backgroundAdjustment' => ['right', 'left'][(int) array_get($this->data, 'alignment', 0)],
        ];

        for ($col = 1; $col <= 3; $col++) {
            if (! array_get($this->data, "link_{$col}", null)) {
                break;
            }
            $data['buttons'][] = [
                 "text" => $this->data["button_text_{$col}"],
                 "link" => $this->data["link_{$col}"],
            ];
        }
        return array_merge(["type" => "CMSHighlightBox"], $data);
    }
}
