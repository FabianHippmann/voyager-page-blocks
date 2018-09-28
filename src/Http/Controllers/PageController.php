<?php

namespace Pvtl\VoyagerPageBlocks\Http\Controllers;

use Pvtl\VoyagerPageBlocks\Page;
use Illuminate\Support\Facades\View;
use Pvtl\VoyagerPageBlocks\Traits\Blocks;
use Pvtl\VoyagerPageBlocks\Http\Controllers\Controller;
use Pvtl\VoyagerPageBlocks\Http\Resources\PageResource;

class PageController extends Controller
{
    use Blocks;

    /**
     * Fetch all pages and their associated blocks
     *
     * @param string $slug
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPage($slug = 'home')
    {
        $request = request();
        $page = Page::where('slug', '=', $slug)->firstOrFail();
        $blocks = $page->blocks()
            ->where('is_hidden', '=', '0')
            ->orderBy('order', 'asc')
            ->get()
            ->map(function ($block) {
                return (object)[
                    'id' => $block->id,
                    'page_id' => $block->page_id,
                    'updated_at' => $block->updated_at,
                    'cache_ttl' => $block->cache_ttl,
                    'template' => $block->template()->template,
                    'data' => $block->cachedData,
                    'path' => $block->path,
                    'type' => $block->type,
                ];
            });

        // Override standard body content, with page block content
        $page['body'] = [
            'blocks' => $this->prepareEachBlock($blocks),
        ];

        // Check that the page Layout and its View exists
        if (empty($page->layout)) {
            $page->layout = 'default';
        }
        if (! View::exists("{$this->viewPath}::layouts.{$page->layout}")) {
            $page->layout = 'default';
        }
        return $this->makeResponse($request, "{$this->viewPath}::modules.pages.default", [
            'page' => $page,
            'layout' => $page->layout,
        ], (new PageResource($page)));
    }
}
