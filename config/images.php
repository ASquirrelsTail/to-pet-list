<?php


return [
	// Folder path to store image uploads
	'storage'=>env('IMAGE_STORAGE', 'image-uploads/'),
	// Set sizes for social share images
	'width'=>env('IMAGE_WIDTH', 600),
	'height'=>env('IMAGE_HEIGHT', 315),
	// Set watermark path for social share images
	'watermark'=> env('IMAGE_WATERMARK', '/public/images/logo.png'),
	// Set cache control for images
	'cache-control'=> env('IMAGE_CACHE','max-age=31536000'),
];
