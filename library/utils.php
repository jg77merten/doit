<?php

/**
 * Add paths to include_path. Doing such way because in CGI mode .htaccess directive will be ignored
 * and also there's no way to do it platform independent   
 *
 * @access  public
 * @param   string  $path1[, string …]
 */
function set_include_paths() 
{
    $paths = func_get_args();
    set_include_path(implode(PATH_SEPARATOR, array_map('realpath', $paths)));
}


/**
 * Walk round array recursively and apply specified function to the every 
 * element changing it to result. 
 *
 * @param   array   $input
 * @param   callback    $funcname
 */
function array_apply_recursive(&$input, $funcname) 
{
    array_walk_recursive($input, create_function('&$value', '$value = ' . $funcname . '($value);'));
}

/**
 * Check whether element with specified index exists in array. Array is 
 * walked round recursively. 
 *
 * @param   array   $needle
 * @param   mixed   $haystack
 * @return  boolean
 */
function in_array_recursive($needle, $haystack) 
{
    foreach ($haystack as $value) {
        if (is_array($value)) {
            if (in_array_recursive($needle, $value)) {
                return true;
            }
        }
        elseif ($value == $needle) {
            return true;
        }
    }
    return false;
}

/**
 * Return input array keys which values are not arrays (tree leaves). 
 *
 * @param   array   $input
 * @return  array
 */
function array_keys_recursive($input) 
{
    $keys = array();
    foreach ($input as $key => $value) {
        $keys = array_merge($keys, is_array($value) ? array_keys_recursive($value) : array($key));
    }
    return array_unique($keys);
}

/**
 * Expands complex array to the plain one.
 *
 * @param   array   &$values
 * @param   array   $input
 * @param	boolean $preserve_keys
 * @return  array
 */
function array_values_recursive(array &$values, array $input, $preserve_keys = false)
{
    foreach ($input as $key => $value) {
		if (is_array($value)) {
			array_values_recursive($values, $value, $preserve_keys);
		} else {
			if ($preserve_keys) {
				$values[$key] = $value;
			} else {
				$values[] = $value;
			}
		}
	}
}

/**
 * Return input array values with keys specified. Elements order in array is
 * preserved. 
 *
 * @param   array   $input
 * @param   array   $keys
 * @param   mixed   $default
 * @return  array
 */
function array_extract($input, $keys, $default = null) 
{
    $result = array();
    
    foreach ($keys as $key) {
        if (array_key_exists($key, $input)) {
            $result[$key] = $input[$key];
        } elseif (!is_null($default)) {
            if (is_array($default) && array_key_exists($key, $default)) {
                $result[$key] = $default[$key];
            } else {
                $result[$key] = $default;
            }
        }
    }
    return $result;
}

/**
 * @desc Check whether input array is numeric (i.e. list)
 *
 * @param   array   $array
 * @return  boolean
 */
function is_numeric_array($array) 
{
    return is_array($array) && array_keys($array) === range(0, count($array) - 1);
}

/**
 * Return input array value with key specified or default if it doesn't exists. 
 *
 * @param   array   $array
 * @param   mixed   $key
 * @param   mixed   $default
 * @return  mixed
 */
function get_array_element($array, $key, $default = null) 
{
    return array_key_exists($key, $array) ? $array[$key] : $default;
}

/**
 * Move element with specified key to the specified position in the input 
 * array. 
 *
 * @param   array   $input
 * @param   mixed   $key
 * @param   integer $position
 * @return  boolean
 */
function array_set_element_position(&$input, $key, $position) 
{
    if (false === ($current = array_search($key, array_keys($input)))
        || $current == $position) {
        return false;
    }
    
    // make slices preserve original keys
    switch (true) 
    {
        case $current < $position;
            $slices = array(
                array_slice($input, 0, $current, true),
                array_slice($input, $current + 1, $position - $current, true),
                array_slice($input, $current, 1, true),
                array_slice($input, $position, count($input), true),
            );
            break;
        case $current > $position;
            $slices = array(
                array_slice($input, 0, $position, true),
                array_slice($input, $current, 1, true),
                array_slice($input, $position, $current - $position, true),
                array_slice($input, $current, count($input), true),
            );
            break;
    }
    
    // zeroize original input array and build it using slices
    $input = array();
    foreach ($slices as $slice) {
        $input = $input + $slice;
    }
    return true;
}

/**
 * Generate random file name with specified extension if given
 *
 * @param   string $ext
 * @return  string
 */
function generate_file_name($ext = null)
{
    $file_name = generate_hash(8);
    if ($ext) {        
        $file_name .= '.' . $ext;
    }
    
    return  $file_name;
}

function generate_hash($length = 32)
{
    return substr(md5(uniqid()), 0, $length);
}

/**
 * @desc Return value for specified directive in bytes. 
 *
 * @param   string  $varname
 * @return  integer
 */
function ini_get_bytes($varname) 
{
   $value = trim(ini_get($varname));
   $last = strtolower($value{strlen($value)-1});
   switch ($last) {
       case 'g':
           $value *= 1024;
       case 'm':
           $value *= 1024;
       case 'k':
           $value *= 1024;
   }
   return (int)$value;
}


/**
 * Convert string from "foo-bar-baz" notation into "fooBarBaz" (Camel case?). 
 *
 * @param   string  $string
 * @return  string
 */
function str_camelize($string) 
{
    return preg_replace('/(^|\-)([a-z])/e', "strtoupper('\\2')", $string);
}

/**
* Uppercase the first character after underscore
* 
* @param string $string
* @return string
*/
function uc_underscore($string) 
{
    return preg_replace('/_([a-z])/e', "'_' . strtoupper('\\1')", $string);
}

/**
 * Add specified scheme to the given url. Default scheme is "http"
 * 
 * @param string $url
 * @param string $scheme
 */
function add_scheme($url, $scheme = 'http') 
{
    $url = trim($url);
    if (!strlen($url)) {
        return '';
    }
    
    if (is_null(parse_url($url, PHP_URL_SCHEME))) {
        $url = $scheme . '://' . ltrim($url, '/');
    }
    
    return $url;
}

/**
* Recursively iterate through the given directory and execute callback function 
* on each found file
* 
* @param string $dir
* @param mixed $callback_func
* @param mixed $callback_params
*/
function iterate_resursive($dir, $callback_func, $callback_params = null) 
{
    if (!is_dir($dir)) { return; }
    $dir = rtrim($dir, '/');
    
    $handler = dir($dir);
    
    while (false !== ($file = $handler->read())) {
        if ( $file == '.' || $file == '..' ) { continue; }
        
        $path = $dir . '/' . $file;
        if (is_dir($path)) {
            iterate_resursive($path, $callback_func, $callback_params);
        } else {
            call_user_func($callback_func, $path, $callback_params);
        }
    }
    
    $handler->close();
}

/**
* Format date according to the given format
* 
* @param string $date
* @param string $format
* @return string
*/
function format_date($date, $format) 
{
    return date($format, strtotime($date));
}
if (!function_exists('http_build_url')) {
    
    /**
    * Build an URL
    * 
    * @param string $url
    * @param array $parts
    * @return string
    */  
    function http_build_url($url, array $parts) 
    {
        $url = parse_url($url);
        
        $parsed = $parts + $url;
        
        if (isset($parsed['scheme'])) {
            $sep = (strtolower($parsed['scheme']) == 'mailto' ? ':' : '://');
            $url = $parsed['scheme'] . $sep;
        } else {
            $url = '';
        }
        
        // Isn't password or user name defined?
        if (isset($parsed['pass'])) {
            $url .= "$parsed[user]:$parsed[pass]@";
        } elseif (isset($parsed['user'])) {
            $url .= "$parsed[user]@";
        }
        // QUERY_STRING represented as array?
        if (@!is_scalar($parsed['query'])) {
            // Convert to the string.
            $parsed['query'] = http_build_query($parsed['query']);
        }
        
        // Assemble Url.
        if (isset($parsed['host']))     $url .= $parsed['host'];
        if (isset($parsed['port']))     $url .= ":".$parsed['port'];
        if (isset($parsed['path']))     $url .= $parsed['path'];
        if (isset($parsed['query']))    $url .= "?".$parsed['query'];
        if (isset($parsed['fragment'])) $url .= "#".$parsed['fragment']; 
        
        return $url; 
    }
}

if (!function_exists('lcfirst')) {
    
    /**
    * Make a string's first character lowercase
    * 
    * @param string $str
    * @return string
    */
    function lcfirst($str) 
    {
        $str[0] = strtolower($str[0]);
        return (string)$str;
    }
}

function formatBytes($size, $precision = 2)
{
    if (0 == $size) {
        return '0B';
    }

    $base = log($size) / log(1024);
    $suffixes = array('', 'K', 'M', 'G', 'T');

    return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)] . 'B';
}

/**
 * Remove file whether it directory or file 
 * @param string $file
 */
function runlink($file)
{
	if(is_file($file)) {
		@unlink($file);
	} elseif(is_dir($file)) {
		$scan = glob(rtrim($file,'/').'/*');
		foreach($scan as $index=>$path){
			runlink($path);
		}
		@rmdir($file);
	}
}

function pcgbasename($param, $suffix=null)
{
	if ( $suffix ) {
		$tmpstr = ltrim(substr($param, strrpos($param, DIRECTORY_SEPARATOR) ), DIRECTORY_SEPARATOR);
		if ( (strpos($param, $suffix)+strlen($suffix) )  ==  strlen($param) ) {
			return str_ireplace( $suffix, '', $tmpstr);
		} else {
			return ltrim(substr($param, strrpos($param, DIRECTORY_SEPARATOR) ), DIRECTORY_SEPARATOR);
		}
	} else {
		return ltrim(substr($param, strrpos($param, DIRECTORY_SEPARATOR) ), DIRECTORY_SEPARATOR);
	}
}
