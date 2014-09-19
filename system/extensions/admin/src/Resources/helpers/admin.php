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
          $path = is_null($sub) ? ADMIN_BASE : ADMIN_BASE.'/'.$sub;
          return base_url($path);
     }
}