<?php


return [
	// Set sizes for social share images
	'width'=>env('IMAGE_WIDTH', 600),
	'height'=>env('IMAGE_HEIGHT', 315),
	// Set watermark path for social share images
	'watermark'=> env('IMAGE_WATERMARK', '/public/images/logo.png'),
];
