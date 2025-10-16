<?php

namespace App\Http\Livewire\product;

use Livewire\Component;

class manage extends Component
{
    /** يمكن تمرير أسماء الروتات من الاستدعاء */
    public string $unitsRoute       = 'units.index';
    public string $categoriesRoute  = 'categories.index';
    public string $productsRoute    = 'product.index';
    public string $settingsRoute    = 'settings.general'; // هنفحص وجوده بـ Route::has

    /** إظهار/إخفاء أي كارت لو حبيت */
    public bool $showUnitsCard      = true;
    public bool $showCategoriesCard = true;
    public bool $showProductsCard   = true;
    public bool $showSettingsCard   = true;

    public function render()
    {
        return view('livewire.product.manage');
    }
}
