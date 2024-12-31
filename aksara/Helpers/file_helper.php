<?php

/**
 * This file is part of Aksara CMS, both framework and publishing
 * platform.
 *
 * @author     Aby Dahana <abydahana@gmail.com>
 * @copyright  (c) Aksara Laboratory <https://aksaracms.com>
 * @license    MIT License
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the LICENSE.txt file.
 *
 * When the signs is coming, those who don't believe at "that time"
 * have only two choices, commit suicide or become brutal.
 */

if (! function_exists('get_file')) {
    /**
     * Get URL of uploaded file
     *
     * @param mixed|null $path
     * @param mixed|null $file
     */
    function get_file($path = null, $file = null)
    {
        return base_url(UPLOAD_PATH . '/' . ($path ? $path . '/' : null) . $file);
    }
}

if (! function_exists('get_image')) {
    /**
     * Get URL of uploaded image
     *
     * @param mixed|null $type
     * @param mixed|null $name
     * @param mixed|null $dimension
     */
    function get_image($type = null, $name = null, $dimension = null)
    {
        if ('thumb' == $dimension) {
            if (! file_exists(UPLOAD_PATH . '/' . ($type ? $type . '/' : null) . 'thumbs/placeholder.png')) {
                try {
                    if ($type && ! is_dir(UPLOAD_PATH . '/' . $type . '/thumbs')) {
                        // Try to make directory
                        mkdir(UPLOAD_PATH . '/' . $type . '/thumbs', 0755, true);
                    }

                    // Copy placeholder image
                    copy(UPLOAD_PATH . '/placeholder_thumb.png', UPLOAD_PATH . '/' . ($type ? $type . '/' : null) . 'thumbs/placeholder.png');
                } catch (\Throwable $e) {
                    // Keep silent
                }
            }

            $placeholder = UPLOAD_PATH . '/' . ($type ? $type . '/' : null) . 'thumbs/placeholder.png';
            $file = UPLOAD_PATH . '/' . ($type ? $type . '/' : null) . 'thumbs/' . $name;
        } elseif ('icon' == $dimension) {
            if (! file_exists(UPLOAD_PATH . '/' . ($type ? $type . '/' : null) . 'icons/placeholder.png')) {
                try {
                    if ($type && ! is_dir(UPLOAD_PATH . '/' . $type . '/icons')) {
                        // Try to make directory
                        mkdir(UPLOAD_PATH . '/' . $type . '/icons', 0755, true);
                    }

                    // Copy placeholder image
                    copy(UPLOAD_PATH . '/placeholder_icon.png', UPLOAD_PATH . '/' . ($type ? $type . '/' : null) . 'icons/placeholder.png');
                } catch (\Throwable $e) {
                    // Keep silent
                }
            }

            $placeholder = UPLOAD_PATH . '/' . ($type ? $type . '/' : null) . 'icons/placeholder.png';
            $file = UPLOAD_PATH . '/' . ($type ? $type . '/' : null) . 'icons/' . $name;
        } else {
            if (! file_exists(UPLOAD_PATH . '/' . ($type ? $type . '/' : null) . 'placeholder.png')) {
                try {
                    if ($type && ! is_dir(UPLOAD_PATH . '/' . $type)) {
                        // Try to make directory
                        mkdir(UPLOAD_PATH . '/' . $type, 0755, true);
                    }

                    // Copy placeholder image
                    copy(UPLOAD_PATH . '/placeholder.png', UPLOAD_PATH . '/' . ($type ? $type . '/' : null) . 'placeholder.png');
                } catch (\Throwable $e) {
                    // Keep silent
                }
            }

            $placeholder = UPLOAD_PATH . '/' . ($type ? $type . '/' : null) . 'placeholder.png';
            $file = UPLOAD_PATH . '/' . ($type ? $type . '/' : null) . $name;
        }

        if (is_file($file) && file_exists($file)) {
            $image = $file;
        } else {
            $image = $placeholder;
        }

        $method = substr(uri_string(), strrpos(uri_string(), '/') + 1);

        if ((in_array(service('request')->getGet('method'), ['print', 'embed', 'pdf', 'download']) || 'document' == service('request')->getGet('r')) && 'print' != $method && 'embed' != $method) {
            $type = pathinfo(ROOTPATH . $image, PATHINFO_EXTENSION);
            $data = file_get_contents($image);
            return 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        return base_url($image);
    }
}

if (! function_exists('resize_image')) {
    /**
     * Image resize
     *
     * @param mixed|null $path
     * @param mixed|null $file
     */
    function resize_image($source = null)
    {
        if (file_exists($source)) {
            $path = dirname($source);
            $filename = basename($source);

            try {
                if (! is_dir($path . '/thumbs')) {
                    // Directory is not exists, create one
                    mkdir($path . '/thumbs', 0755, true);
                    copy(UPLOAD_PATH . '/placeholder_thumb.png', $path . '/thumbs/placeholder.png');
                }

                if (! is_dir($path . '/icons')) {
                    // Directory is not exists, create one
                    mkdir($path . '/icons', 0755, true);
                    copy(UPLOAD_PATH . '/placeholder_icon.png', $path . '/icons/placeholder.png');
                }
                
                // Uploaded file is image format, prepare image manipulation
                $imageinfo = getimagesize($source);
                $source = new \CodeIgniter\Files\File($source);
                $master_dimension = ($imageinfo[0] > $imageinfo[1] ? 'width' : 'height');
                $original_dimension = (is_numeric(IMAGE_DIMENSION) ? IMAGE_DIMENSION : 1024);
                $thumbnail_dimension = (is_numeric(THUMBNAIL_DIMENSION) ? THUMBNAIL_DIMENSION : 256);
                $icon_dimension = (is_numeric(ICON_DIMENSION) ? ICON_DIMENSION : 64);
                
                // Load image manipulation library
                $image = \Config\Services::image('gd');
    
                if ($source->getMimeType() != 'image/gif' && $imageinfo[0] > $original_dimension) {
                    // Resize image and move to upload directory
                    $image->withFile($source)
                        ->resize($original_dimension, $original_dimension, true, $master_dimension)
                        ->save($path . '/' . $filename);
                }

                // Create thumbnail
                if ($image->withFile($source)->resize($thumbnail_dimension, $thumbnail_dimension, true, $master_dimension)->save($path . '/thumbs/' . $filename)) {
                    // Crop image after resized
                    $image->withFile($path . '/thumbs/' . $filename)
                        ->fit($thumbnail_dimension, $thumbnail_dimension, 'center')
                        ->save($path . '/thumbs/' . $filename);
                }

                // Create icon
                if ($image->withFile($source)->resize($icon_dimension, $icon_dimension, true, $master_dimension)->save($path . '/icons/' . $filename)) {
                    // Crop image after resized
                    $image->withFile($path . '/icons/' . $filename)
                        ->fit($icon_dimension, $icon_dimension, 'center')
                        ->save($path . '/icons/' . $filename);
                }
            } catch (\Throwable $e) {
                // Safe abstraction
            }
        }
    }
}

if (! function_exists('get_filesize')) {
    /**
     * Get file size
     *
     * @param mixed|null $path
     * @param mixed|null $file
     */
    function get_filesize($path = null, $file = null)
    {
        $size = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $bytes = @filesize(FCPATH . UPLOAD_PATH . '/' . ($path ? $path . '/' : null) . $file);
        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf('%.2f', ($bytes / pow(1024, $factor))) . @$size[$factor];
    }
}
