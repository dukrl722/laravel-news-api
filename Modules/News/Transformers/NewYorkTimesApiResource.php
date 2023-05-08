<?php

namespace Modules\News\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewYorkTimesApiResource extends JsonResource
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
            "author" => $this->byline->original ?? "Unknown",
            "title" => $this->abstract,
            "description" => $this->lead_paragraph,
            "section" => $this->section_name,
            "url" => $this->web_url,
            "img" => 'https://static01.nyt.com/' . $this->multimedia[0]->url,
            "published_at" => date('Y-m-d H:i:s', strtotime($this->pub_date))
        ];
    }
}
