<?php
/**
 * Plugin Name:     Default User Avatar
 * Plugin URL:      https://rwsite.ru
 * Description:     Change default user avatar
 * Version:         1.0.8
 * Text Domain:     wp-addon
 * Domain Path:     /languages
 * Author:          Aleksey Tikhomirov <alex@rwite.ru>
 * Author URI:      https://rwsite.ru
 *
 * Tags: avatar, default avatar, user, user avatar
 * Requires at least: 4.6
 * Tested up to: 5.6.0
 * Requires PHP: 7.2+
 *
 * @package WordPress Addon
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if(!class_exists('DefaultUserAvatar')):

class DefaultUserAvatar
{
    public $url;

    public static $plugin_url;
    public static $plugin_path;

    public function __construct( string $url = null)
    {
        add_filter('avatar_defaults',   [$this, 'new_default_avatar'], 1);
        add_filter('get_avatar',        [$this, 'media_get_avatar'], 1, 6);

        self::$plugin_url = defined( 'RW_PLUGIN_URL' ) ? RW_PLUGIN_URL : plugin_dir_url( __FILE__ );

        $this->url = $url ?? self::$plugin_url . 'assets/images/user_gray.svg';
    }

    /**
     * Свой вариант аватарки по-умолчанию.
     *
     * @param $avatar_defaults
     *
     * @return array
     */
    public function new_default_avatar($avatar_defaults): array
    {
        $avatar_defaults[$this->url] = __('Custom Default Avatar', 'wp-addon');
        return $avatar_defaults;
    }

    /**
     * Свой вариант аватарки по-умолчанию. Заменяем картинку при отображении списка аватаров.
     *
     * @param $avatar
     * @param $id_or_email
     * @param $size
     * @param $default
     * @param $alt
     * @param $args
     *
     * @return string - html код аватарки
     */
    public function media_get_avatar($avatar, $id_or_email, $size, $default, $alt, $args): string
    {
         if ( empty($avatar) or $args['default'] === $this->url or mb_strpos($args['default'], $this->url_part) ) {
            $avatar = '<img src="' .  $this->url . '" width="'. $args['width'] .'px" height="'. $args['height'] .'px" class="avatar '. $args['loading'] .'" 
            alt="' . __('User avatar', 'wp-addon') . '">';
        }
        return $avatar;
    }
}

return new DefaultUserAvatar();
endif;