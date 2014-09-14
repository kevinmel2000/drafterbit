<?php

if ( ! function_exists('comment'))
{
    /**
     * admin url
     *
     * @param string $sub
     * @return string
     */
     function comment($id)
     {
          $html = "<div>";
          $html .= label('Name');
          $html .= input_text('name');
          $html .= label('Email');
          $html .= input_text('email');
          $html .= label('Website');
          $html .= input_text('website');
          $html .= label('Comment');
          $html .= input_textarea('comment');
          $html .= "<div>";
          return $html;
     }
}