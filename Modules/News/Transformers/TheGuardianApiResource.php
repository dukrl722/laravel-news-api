<?php

namespace Modules\News\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TheGuardianApiResource extends JsonResource
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
            "author" => "The Guardian",
            "title" => $this->webTitle,
            "description" => $this->webTitle,
            "section" => $this->sectionName,
            "url" => $this->webUrl,
            "img" => "",
            "published_at" => date('Y-m-d H:i:s', strtotime($this->webPublicationDate))
        ];
    }
}
