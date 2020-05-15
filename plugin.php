<?php
/*
Plugin Name: Force Thumbscale
Plugin URI: https://github.com/bfiessinger/wordpress-force-thumbscale
Description: Maintains aspect ratio of thumbnails even if the source image is smaller than the registerd image size
Version: 1.0.0
Author: Bastian Fießinger
Author URI: https://github.com/bfiessinger
License: GPLv3
*/

namespace bf\thumbscale;

class scale {

	static function image_resize_dimensions($default, $orig_w, $orig_h, $dest_w, $dest_h, $crop) {

		if ( $orig_w <= 0 || $orig_h <= 0 ) {
			return false;
		}

		// At least one of $dest_w or $dest_h must be specific.
		if ( $dest_w <= 0 && $dest_h <= 0 ) {
			return false;
		}

		if ( $crop ) {

				/*
				* Crop the largest possible portion of the original image that we can size to $dest_w x $dest_h.
				* Note that the requested crop dimensions are used as a maximum bounding box for the original image.
				* If the original image's width or height is less than the requested width or height
				* only the greater one will be cropped.
				* For example when the original image is 600x300, and the requested crop dimensions are 400x400,
				* the resulting image will be 400x300.
				*/
				$aspect_ratio = $dest_w / $dest_h;
				
				$data_min_width = min( $dest_w, $orig_w );
				$data_min_height = min( $dest_h, $orig_h );

				$width_calc_aspect_ratio = (int) round( $data_min_height * $aspect_ratio );
				$height_calc_aspect_ratio = (int) round( $data_min_width / $aspect_ratio );

				if ( $dest_w > $dest_h ) {

					$new_w = $data_min_width;
					$new_h = $height_calc_aspect_ratio;

				} elseif ( $dest_w < $dest_h ) {

					$new_w = $width_calc_aspect_ratio;
					$new_h = $data_min_height;

				} else {

					$new_w = max( $data_min_width, $data_min_height );
					$new_h = max( $data_min_width, $data_min_height );

				}

				if ( ! $new_w ) {
					$new_w = $width_calc_aspect_ratio;
				}

				if ( ! $new_h ) {
					$new_h = $height_calc_aspect_ratio;
				}

				$size_ratio = max( $new_w / $orig_w, $new_h / $orig_h );

				$crop_w = round( $new_w / $size_ratio );
				$crop_h = round( $new_h / $size_ratio );

				if ( ! is_array( $crop ) || count( $crop ) !== 2 ) {
						$crop = array( 'center', 'center' );
				}

				list( $x, $y ) = $crop;

				if ( 'left' === $x ) {
						$s_x = 0;
				} elseif ( 'right' === $x ) {
						$s_x = $orig_w - $crop_w;
				} else {
						$s_x = floor( ( $orig_w - $crop_w ) / 2 );
				}

				if ( 'top' === $y ) {
						$s_y = 0;
				} elseif ( 'bottom' === $y ) {
						$s_y = $orig_h - $crop_h;
				} else {
						$s_y = floor( ( $orig_h - $crop_h ) / 2 );
				}

		} else {

				// Resize using $dest_w x $dest_h as a maximum bounding box.
				$crop_w = $orig_w;
				$crop_h = $orig_h;

				$s_x = 0;
				$s_y = 0;

				list( $new_w, $new_h ) = wp_constrain_dimensions( $orig_w, $orig_h, $dest_w, $dest_h );

		}

		if ( wp_fuzzy_number_match( $new_w, $orig_w ) && wp_fuzzy_number_match( $new_h, $orig_h ) ) {

				// The new size has virtually the same dimensions as the original image.

				/**
				 * Filters whether to proceed with making an image sub-size with identical dimensions
				 * with the original/source image. Differences of 1px may be due to rounding and are ignored.
				 *
				 * @since 5.3.0
				 *
				 * @param bool $proceed The filtered value.
				 * @param int  $orig_w  Original image width.
				 * @param int  $orig_h  Original image height.
				 */
				$proceed = (bool) apply_filters( 'wp_image_resize_identical_dimensions', false, $orig_w, $orig_h );

				if ( ! $proceed ) {
					return false;
				}

		}

		// The return array matches the parameters to imagecopyresampled().
		// int dst_x, int dst_y, int src_x, int src_y, int dst_w, int dst_h, int src_w, int src_h
		return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );

	}
}

add_filter('image_resize_dimensions', ['bf\thumbscale\scale', 'image_resize_dimensions'], 10, 6);
