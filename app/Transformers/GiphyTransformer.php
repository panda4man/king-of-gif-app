<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class GiphyTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param array $data
     * @return array
     */
    public function transform($data = [])
    {
        return [
            'id'      => $data['id'],
            'url'     => $data['image_url'],
            'width'   => $data['image_width'],
            'height'  => $data['image_height'],
            'caption' => $data['caption'],
        ];
    }
}
