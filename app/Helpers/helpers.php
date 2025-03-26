<?php

use App\Models\StaticBlock;

function staticBlockImage($slug, $imagaPath)
{
    $temp = StaticBlock::where('slug', $slug)->where('is_active', true)->first();
    if (!$temp || empty($temp->content)) {
        return asset($imagaPath);
    } else {
        if (preg_match('/<img\s+[^>]*src=["\'](data:image\/[^"\']+)["\']/i', $temp->content, $matches)) {
            $temp = $matches[1];
        } else {
            $temp = asset($imagaPath);
        }
    }

    return $temp;
}

function staticBlock($slug){
     $temp=StaticBlock::where('slug', $slug)->where('is_active', true)->first();
     return $temp->content;
}

