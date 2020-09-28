<?php
/**
 * Download webfonts locally.
 *
 * @package wptt/font-loader
 * @license https://opensource.org/licenses/MIT
 */

if ( ! class_exists( 'WPTT_WebFont_Loader' ) ) {
	/**
	 * Download webfonts locally.
	 */
	class WPTT_WebFont_Loader {

		/**
		 * The font-format.
		 *
		 * Use "woff" or "woff2".
		 * This will change the user-agent user to make the request.
		 *
		 * @access public
		 *
		 * @since 1.0.0
		 *
		 * @var string
		 */
		public $font_format = 'woff2';

		/**
		 * Get styles from URL.
		 *
		 * @access public
		 *
		 * @since 1.0.0
		 *
		 * @param string $url The URL.
		 *
		 * @return string
		 */
		public function get_styles( $url ) {
			return $this->get_local_font_styles(
				$this->get_cached_url_contents( $url )
			);
		}

		/**
		 * Get styles with fonts downloaded locally.
		 *
		 * @access public
		 *
		 * @since 1.0.0
		 *
		 * @param string $css The styles.
		 *
		 * @return string
		 */
		public function get_local_font_styles( $css ) {

			// Get an array of locally-hosted files.
			$files = $this->get_local_files_from_css( $css );

			// Convert paths to URLs.
			foreach ( $files as $remote => $local ) {
				$files[ $remote ] = str_replace( WP_CONTENT_DIR, content_url(), $local );
			}

			return str_replace(
				array_keys( $files ),
				array_values( $files ),
				$css
			);
		}

		/**
		 * Download files mentioned in our CSS locally.
		 *
		 * @access public
		 *
		 * @since 1.0.0
		 *
		 * @param string $css The CSS we want to parse.
		 *
		 * @return array Returns an array of remote URLs and their local counterparts.
		 */
		public function get_local_files_from_css( $css ) {
			$font_files = $this->get_files_from_css( $css );
			$stored     = get_site_option( 'downloaded_font_files', array() );
			$change     = false; // If in the end this is true, we need to update the cache option.

			// If the fonts folder don't exist, create it.
			if ( ! file_exists( WP_CONTENT_DIR . '/fonts' ) ) {
				$this->get_filesystem()->mkdir( WP_CONTENT_DIR . '/fonts', FS_CHMOD_DIR );
			}

			foreach ( $font_files as $font_family => $files ) {

				// The folder path for this font-family.
				$folder_path = WP_CONTENT_DIR . '/fonts/' . $font_family;

				// If the folder doesn't exist, create it.
				if ( ! file_exists( $folder_path ) ) {
					$this->get_filesystem()->mkdir( $folder_path, FS_CHMOD_DIR );
				}

				foreach ( $files as $url ) {

					// Get the filename.
					$filename  = basename( wp_parse_url( $url, PHP_URL_PATH ) );
					$font_path = $folder_path . '/' . $filename;

					// Check if the file already exists.
					if ( file_exists( $font_path ) ) {

						// Skip if already cached.
						if ( isset( $stored[ $url ] ) ) {
							continue;
						}

						// Add file to the cache and change the $changed var to indicate we need to update the option.
						$stored[ $url ] = $font_path;
						$change         = true;

						// Since the file exists we don't need to proceed with downloading it.
						continue;
					}

					/**
					 * If we got this far, we need to download the file.
					 */

					// require file.php if the download_url function doesn't exist.
					if ( ! function_exists( 'download_url' ) ) {
						require_once wp_normalize_path( ABSPATH . '/wp-admin/includes/file.php' );
					}

					// Download file to temporary location.
					$tmp_path = download_url( $url );

					// Make sure there were no errors.
					if ( is_wp_error( $tmp_path ) ) {
						continue;
					}

					// Move temp file to final destination.
					$success = $this->get_filesystem()->move( $tmp_path, $font_path, true );
					if ( $success ) {
						$stored[ $url ] = $font_path;
						$change         = true;
					}
				}
			}

			// If there were changes, update the option.
			if ( $change ) {
				update_site_option( 'downloaded_font_files', $stored );
			}

			return $stored;
		}

		/**
		 * Get cached url contents.
		 *
		 * If a cache doesn't already exist, get the URL contents from remote
		 * and cache the result.
		 * We're using a transient for caches because because webfonts get updated
		 * and therefore need to be periodically checked for updates.
		 *
		 * @access public
		 *
		 * @since 3.1.0
		 *
		 * @param string $url The URL we want to get the contents from.
		 *
		 * @return string Returns the remote URL contents.
		 */
		public function get_cached_url_contents( $url = '' ) {

			// Try to retrieved cached response from the gfonts API.
			$contents       = false;
			$transient_name = 'url_contents_' . md5( $url );
			$contents       = get_site_transient( $transient_name );

			// If the transient is empty we need to get contents from the remote URL.
			if ( ! $contents ) {

				// Get the contents from remote.
				$contents = $this->get_url_contents( $url );

				// If we got the contents successfully, store them in a transient.
				// We're using a transient and not an option because fonts get updated
				// so we want to be able to get the latest version weekly.
				if ( $contents ) {
					set_site_transient( $transient_name, $contents, WEEK_IN_SECONDS );
				}
			}

			return $contents;
		}

		/**
		 * Get remote file contents.
		 *
		 * @access public
		 *
		 * @since 3.1.0
		 *
		 * @param string $url The URL we want to get the contents from.
		 *
		 * @return string Returns the remote URL contents.
		 */
		public function get_url_contents( $url = '' ) {

			/**
			 * The user-agent we want to use.
			 *
			 * The default user-agent is the only one compatible with woff (not woff2)
			 * which also supports unicode ranges.
			 */
			$user_agent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5) AppleWebKit/603.3.8 (KHTML, like Gecko) Version/10.1.2 Safari/603.3.8';

			// Switch to a user-agent supporting woff2 if we don't need to support IE.
			if ( 'woff2' === $this->font_format ) {
				$user_agent = 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:73.0) Gecko/20100101 Firefox/73.0';
			}

			// Get the response.
			$response = wp_remote_get( $url, array( 'user-agent' => $user_agent ) );

			// Early exit if there was an error.
			if ( is_wp_error( $response ) ) {
				return;
			}

			// Get the CSS from our response.
			$contents = wp_remote_retrieve_body( $response );

			// Early exit if there was an error.
			if ( is_wp_error( $contents ) ) {
				return;
			}

			return $contents;
		}

		/**
		 * Get font files from the CSS.
		 *
		 * @access public
		 *
		 * @since 1.0.0
		 *
		 * @param string $css The CSS we want to parse.
		 *
		 * @return array Returns an array of font-families and the font-files used.
		 */
		public function get_files_from_css( $css ) {

			$font_faces = explode( '@font-face', $css );

			$result = array();

			// Loop all our font-face declarations.
			foreach ( $font_faces as $font_face ) {

				// Make sure we only process styles inside this declaration.
				$style = explode( '}', $font_face )[0];

				// Sanity check.
				if ( false === strpos( $style, 'font-family' ) ) {
					continue;
				}

				// Get an array of our font-families.
				preg_match_all( '/font-family.*?\;/', $style, $matched_font_families );

				// Get an array of our font-files.
				preg_match_all( '/url\(.*?\)/i', $style, $matched_font_files );

				// Get the font-family name.
				$font_family = 'unknown';
				if ( isset( $matched_font_families[0] ) && isset( $matched_font_families[0][0] ) ) {
					$font_family = rtrim( ltrim( $matched_font_families[0][0], 'font-family:' ), ';' );
					$font_family = trim( str_replace( array( "'", ';' ), '', $font_family ) );
					$font_family = sanitize_key( strtolower( str_replace( ' ', '-', $font_family ) ) );
				}

				// Make sure the font-family is set in our array.
				if ( ! isset( $result[ $font_family ] ) ) {
					$result[ $font_family ] = array();
				}

				// Get files for this font-family and add them to the array.
				foreach ( $matched_font_files as $match ) {

					// Sanity check.
					if ( ! isset( $match[0] ) ) {
						continue;
					}

					// Add the file URL.
					$result[ $font_family ][] = rtrim( ltrim( $match[0], 'url(' ), ')' );
				}

				// Make sure we have unique items.
				// We're using array_flip here instead of array_unique for improved performance.
				$result[ $font_family ] = array_flip( array_flip( $result[ $font_family ] ) );
			}
			return $result;
		}

		/**
		 * Get the filesystem.
		 *
		 * @access protected
		 *
		 * @since 1.0.0
		 *
		 * @return WP_Filesystem
		 */
		protected function get_filesystem() {
			global $wp_filesystem;

			// If the filesystem has not been instantiated yet, do it here.
			if ( ! $wp_filesystem ) {
				if ( ! function_exists( 'WP_Filesystem' ) ) {
					require_once wp_normalize_path( ABSPATH . '/wp-admin/includes/file.php' );
				}
				WP_Filesystem();
			}
			return $wp_filesystem;
		}

		/**
		 * Set the font-format to be used.
		 *
		 * @access public
		 *
		 * @since 1.0.0
		 *
		 * @param string $format The format to be used. Use "woff" or "woff2".
		 *
		 * @return void
		 */
		public function set_font_format( $format = 'woff2' ) {
			$this->font_format = $format;
		}
	}
}

if ( ! function_exists( 'wptt_get_webfont_styles' ) ) {
	/**
	 * Get styles for a webfont.
	 *
	 * This will get the CSS from the remote API,
	 * download any fonts it contains,
	 * replace references to remote URLs with locally-downloaded assets,
	 * and finally return the resulting CSS.
	 *
	 * @since 1.0.0
	 *
	 * @param string $url    The URL of the remote webfont.
	 * @param string $format The font-format. If you need to support IE, change this to "woff".
	 *
	 * @return string Returns the CSS.
	 */
	function wptt_get_webfont_styles( $url, $format = 'woff2' ) {
		$font = new WPTT_WebFont_Loader();
		$font->set_font_format( $format );
		return $font->get_styles( $url );
	}
}
