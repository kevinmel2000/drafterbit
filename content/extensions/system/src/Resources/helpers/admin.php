<?php

if ( ! function_exists('admin_url'))
{
     /**
     * admin url
     *
     * @param string $sub
     * @return string
     */
     function admin_url($sub = null)
     {
          $admin = app('config')['path.admin'];
          $path = is_null($sub) ? $admin : $admin.'/'.$sub;
          return base_url($path);
     }
}