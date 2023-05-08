<?php

if (!function_exists('storeMedia')) :
    function storeMedia($obj, $img, $legend, $collection, $update = false)
    {
        if ($img) :
            if ($update) :
                $obj->clearMediaCollection($collection);
            endif;

            return $obj->addMedia($img)
                ->usingName($legend)
                ->usingFileName(md5($img->getClientOriginalName() . time()) . '.' . $img->getClientOriginalExtension())
                ->toMediaCollection($collection);
        endif;
    }
endif;
