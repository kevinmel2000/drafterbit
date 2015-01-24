<?php namespace Drafterbit\Extensions\System\Models;

class Dashboard extends \Drafterbit\Framework\Model
{

    public function recent()
    {
        $logs = $this->model('Log')->recent();

        foreach ($logs as &$log) {
            $log = (object)$log;
            $log->time = $this->get('time')->createFromTimestamp($log->time)->diffForHumans();
            $log->formattedMsg = $this->formatLog($log->message);
        }

        $data['logs'] = $logs;

        return $this->get('template')->render('@system/dashboard/recent', $data);
    }

    public function info()
    {
        $stat = $this->get('app')->getStat();

        // site info
        $stat['User(s)'] = count($this->get('cache')->fetch('users'));
        $stat['OS'] = $this->getOs();
        $stat['PHP'] = phpversion();
        $stat['DB'] = $this->get('db')->getServerVersion();
        $stat['Time'] = date('H:i:s');
        $stat['Theme'] = $this->get('themes')->current();
        $stat['Server'] = $this->get('input')->server('SERVER_SOFTWARE');
        $data['stat'] = $stat;

        return $this->get('template')->render('@system/dashboard/info', $data);
    }

    /**
     * Format log message
     *
     * @param  string $line
     * @return string
     */
    public function formatLog($line)
    {
        // we'll find words formatted like "@user:1"
        // then replace it with format function
        // defined in each extension.
        return preg_replace_callback(
            '/@(\w+:[1-9]+)/',
            function ($matches) {

                $temp = explode(':', $matches[1]);
                
                $entity = current($temp);
                $id = end($temp);

                return $this->getEntityLabel($entity, $id);
            },
            $line
        );
    }

    /**
     * Get entity label for log message
     *
     * @return string
     */
    private function getEntityLabel($entity, $id)
    {
        return $this->get('app')->getLogEntityLabel($entity, $id);
    }

    private function getOs()
    {
        $os_platform = "Unknown";

        $os_array = array(
            '/windows nt 6.3/i'     =>  'Windows 8.1',
            '/windows nt 6.2/i'     =>  'Windows 8',
            '/windows nt 6.1/i'     =>  'Windows 7',
            '/windows nt 6.0/i'     =>  'Windows Vista',
            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
            '/windows nt 5.1/i'     =>  'Windows XP',
            '/windows xp/i'         =>  'Windows XP',
            '/windows nt 5.0/i'     =>  'Windows 2000',
            '/windows me/i'         =>  'Windows ME',
            '/win98/i'              =>  'Windows 98',
            '/win95/i'              =>  'Windows 95',
            '/win16/i'              =>  'Windows 3.11',
            '/macintosh|mac os x/i' =>  'Mac OS X',
            '/mac_powerpc/i'        =>  'Mac OS 9',
            '/linux/i'              =>  'Linux',
            '/ubuntu/i'             =>  'Ubuntu',
            '/iphone/i'             =>  'iPhone',
            '/ipod/i'               =>  'iPod',
            '/ipad/i'               =>  'iPad',
            '/android/i'            =>  'Android',
            '/blackberry/i'         =>  'BlackBerry',
            '/webos/i'              =>  'Mobile'
        );

        foreach ($os_array as $regex => $value) {
            if (preg_match($regex, $this->get('input')->server('HTTP_USER_AGENT'))) {
                $os_platform = $value;
            }
        }

        return $os_platform;
    }
}
