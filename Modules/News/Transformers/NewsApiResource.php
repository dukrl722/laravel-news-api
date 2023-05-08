<?php

namespace Modules\News\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "author" => $this->author ?? "Unknown",
            "title" => $this->title,
            "description" => $this->description,
            "section" => $this->source->name,
            "url" => $this->url,
            "img" => $this->urlToImage,
            "published_at" => date('Y-m-d H:i:s', strtotime($this->publishedAt))
        ];
    }
}
