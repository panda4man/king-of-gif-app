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
        $resData = [
            'id' => $data['id'],
        ];

        if(isset($data['images'])) {
            //This will be in the case many gifs are returned
            $original = $data['images']['original'];
            $resData['url'] = $original['url'];
            $resData['width'] = $original['width'];
            $resData['height'] = $original['height'];

            if(isset($data['images']['caption'])) {
                $resData['caption'] = $data['images']['caption'];
            }
        } else {
            //This will usually be a single gif look up response
            $resData['url'] = $data['image_url'];
            $resData['width'] = $data['image_width'];
            $resData['height'] = $data['image_height'];

            if(isset($data['title'])) {
                $resData['caption'] = $data['title'];
            }
        }

        return $resData;
    }
}
