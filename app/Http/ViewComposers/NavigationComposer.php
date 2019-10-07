<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 03/04/2018
 * Time: 10:41
 */

namespace App\Http\ViewComposers;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NavigationComposer
{
    public $categories;

    public function __construct()
    {
        $this->categories = Category::where('status_id', 1)->get();
    }

    public function compose(View $view)
    {
        $data = [
            'categories'         => $this->categories,
        ];
        $view->with($data);
    }
}
