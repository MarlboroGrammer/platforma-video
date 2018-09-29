#!/usr/bin/bash
link=$1

path="/storage1"

original=$path
original+="/Originals/"
original+=$link
original+=".mp4"

encoded=$path
encoded+="/Encodes/"
encoded+=$link
encoded480=$encoded
encoded720=$encoded
encoded480+="_h264_480.mp4"
encoded720+="_h264_720.mp4"

thumbnail=$path
thumbnail+="/Originals/thumbnails/"
thumbnail+=$link
thumbnail+=".jpg"

rm -f $original
killall ffmpeg
rm -f "$encoded720"
rm -f "$encoded480"
rm -f $thumbnail