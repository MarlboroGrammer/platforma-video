#!/usr/bin/bash
link=$1

original="/home/gekijodouga/gejikodouga_platforma/platforma-video/Originals/"
original+=$link
original+=".mp4"

encoded="/home/gekijodouga/gejikodouga_platforma/platforma-video/Encodes/"
encoded+=$link
encoded480=$encoded
encoded720=$encoded
encoded480+="_h264_480.mp4"
encoded720+="_h264_720.mp4"

thumbnail="/home/gekijodouga/gejikodouga_platforma/platforma-video/Originals/thumbnails/"
thumbnail+=$link
thumbnail+=".png"

rm -f $original

killall ffmpeg

if [-e $encoded720]
then
	rm -f "$encoded720"
fi
if [-e $encoded480]
then
	rm -f "$encoded480"
fi

rm -f $thumbnail