#!/usr/bin/bash
link=$1

path="/storage1/"

original_img=$path
original_img+="Originals/thumbnails/"
original_img+=$link
original_img+=".png"

processed_img=$path
processed_img+="Originals/thumbnails/"
processed_img+=$link
processed_img+=".jpg"

convert $original_img -resize 1024x574! -quality 80 $processed_img
rm -f $original_img