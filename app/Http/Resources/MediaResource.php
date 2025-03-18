<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        $mimeType = $this->determineMimeType($this->mime_type);
        return [
            $mimeType => [
            'file_name' => $this->file_name,
            'file_url' => $this->original_url,
            'mime_type' => $this->mime_type,


        ]];
    }
    private function determineMimeType($mimeType): string
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'images';
        }

        if (str_starts_with($mimeType, 'video/')) {
            return 'videos';
        }

        return 'files';
    }

}
