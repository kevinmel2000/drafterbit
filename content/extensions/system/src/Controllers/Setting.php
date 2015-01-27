<?php namespace Drafterbit\Extensions\System\Controllers;

use Drafterbit\Extensions\System\BackendController;

class Setting extends BackendController
{

    public function general()
    {
        $post = $this->get('input')->post();

        if ($post) {
            // @todo validate setting

            $data = $this->setupData($post);
            $this->model('@system\System')->updateSetting($data);
            $this->get('template')->addGlobal('messages', [['text' => "Setting updated", "type" => 'success']]);
        }
        
        $system = $this->model('@system\System');

        $data = [
            'siteName'    => $system->fetch('site.name'),
            'tagLine'     => $system->fetch('site.description'),
            'adminEmail'  => $system->fetch('email'),
            'language'    => $system->fetch('language'),
            'timezone'    => $system->fetch('timezone'),
            'dateFormat'  => $system->fetch('format.date'),
            'timeFormat'  => $system->fetch('format.time'),
            'homepage'    => $system->fetch('homepage'),
            'smtpHost'    => $system->fetch('smtp.host'),
            'smtpPort'    => $system->fetch('smtp.port'),
            'smtpUser'    => $system->fetch('smtp.user'),
            'smtpPass'    => $system->fetch('smtp.pass'),
            'pageOptions' => $this->get('app')->getFrontPageOption(),
            'timezoneIdList' => timezone_identifiers_list(),
            'languageList'   => $this->getLanguageList(),
            'title'       => __('General Setting'),
            'id' => 'setting'
        ];

        return $this->render('@system/setting/general', $data);
    }

    protected function setupData($p)
    {
        $data = array();

        $data['site.name'] = $p['site-name'];
        $data['site.description'] = $p['site-tagline'];
        $data['email'] = $p['email'];
        $data['language'] = $p['language'];
        $data['timezone'] = $p['timezone'];
        $data['format.date'] = $p['format-date'];
        $data['format.time'] = $p['format-time'];

        $data['homepage'] = $p['homepage'];

        $data['smtp.host'] = $p['smtp-host'];
        $data['smtp.port'] = $p['smtp-port'];
        $data['smtp.user'] = $p['smtp-user'];
        $data['smtp.pass'] = $p['smtp-pass'];

        return $data;
    }

    private function getLanguageList()
    {
        return $this->get('translator')->getLocales();
    }
}
