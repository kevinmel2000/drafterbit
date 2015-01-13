<?php namespace Drafterbit\Widgets\Search;

use Drafterbit\System\Widget\Widget;
use Drafterbit\Extensions\System\FrontendController;

class SearchWidget extends Widget {

    public function run()
    {

        return $this->get('twig')->render('search/form.html');
/*        return '<form action="'.base_url('search').'"><input type ="text" name="q">
        <input  type="submit" value="Search">
        </form>';
        */
    }

    function frontEnd()
    {
        return new FrontendController;
    }
}