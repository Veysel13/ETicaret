<?php


namespace App\View\Components;

use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;
class Datatable extends Component
{

    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $sort;
    public $url;
    public $method;
    public $rowClick;
    public $pageLength;
    public $divId;
    public $lengthChange;

    public function __construct($sort = false, $url = '', $method = 'POST', $rowClick = '', $pageLength = 20, $divId = '', $lengthChange = false)
    {
        $this->sort = $sort;
        $this->url = $url;
        $this->method = $method;
        $this->rowClick = $rowClick;
        $this->pageLength = $pageLength;
        $this->divId = $divId;
        $this->lengthChange = $lengthChange;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.datatable');
    }
}
