<?php

namespace App\Http\Livewire\offers;

use Livewire\Component;
use App\models\offers\offers as Offer;
use Illuminate\Support\Arr;

class Manage extends Component
{
    public ?Offer $offer = null;

    // حقول مترجمة
    public $name = ['ar'=>'','en'=>''];
    public $description = ['ar'=>'','en'=>''];

    // خصائص العرض
    public $type = 'percentage';            // percentage|fixed|bxgy|bundle
    public $discount_value = null;          // للـ percentage|fixed
    public $x_qty = null;                   // للـ bxgy
    public $y_qty = null;                   // للـ bxgy
    public $bundle_price = null;            // للـ bundle
    public $max_discount_per_order = null;

    public $is_stackable = true;
    public $priority = 100;
    public $policy = 'highest_priority';    // highest_priority|largest_discount
    public $status = 'active';              // draft|active|paused|expired

    // النطاق الزمني
    public $start_at = null;
    public $end_at = null;
    public $days_of_week = [];              // 1..7 (الاثنين..الأحد حسب نظامك)
    public $hours_from = null;              // HH:ii
    public $hours_to = null;                // HH:ii

    // النطاقات الاختيارية
    public $sales_channel = null;
    public $branch_ids = [];
    public $product_ids = [];
    public $category_ids = [];

    /* ==================== القواعد ==================== */
    protected function rules()
    {
        $base = [
            'name.ar'                  => 'required|string|max:255',
            'name.en'                  => 'required|string|max:255',
            'description.ar'           => 'nullable|string|max:2000',
            'description.en'           => 'nullable|string|max:2000',

            'type'                     => 'required|in:percentage,fixed,bxgy,bundle',
            'is_stackable'             => 'boolean',
            'priority'                 => 'required|integer|min:1|max:100000',
            'policy'                   => 'required|in:highest_priority,largest_discount',
            'status'                   => 'required|in:draft,active,paused,expired',

            'start_at'                 => 'nullable|date',
            'end_at'                   => 'nullable|date|after_or_equal:start_at',

            'days_of_week'             => 'array',
            'days_of_week.*'           => 'integer|min:1|max:7',

            'hours_from'               => 'nullable|date_format:H:i',
            'hours_to'                 => 'nullable|date_format:H:i',

            'sales_channel'            => 'nullable|string|max:20',

            'branch_ids'               => 'array',
            'product_ids'              => 'array',
            'category_ids'             => 'array',

            'max_discount_per_order'   => 'nullable|numeric|min:0',
        ];

        if (in_array($this->type, ['percentage','fixed'])) {
            $base['discount_value'] = $this->type === 'percentage'
                ? 'required|numeric|min:0|max:100'
                : 'required|numeric|min:0';
        }

        if ($this->type === 'bxgy') {
            $base['x_qty'] = 'required|integer|min:1';
            $base['y_qty'] = 'required|integer|min:1';
        }

        if ($this->type === 'bundle') {
            $base['bundle_price'] = 'required|numeric|min:0';
        }

        return $base;
    }

    /* ==================== رسائل التحقق (AR) ==================== */
    protected $messages = [
        'name.ar.required'           => 'الاسم بالعربية مطلوب.',
        'name.en.required'           => 'الاسم بالإنجليزية مطلوب.',
        'discount_value.required'    => 'قيمة الخصم مطلوبة.',
        'discount_value.max'         => 'قيمة النسبة يجب أن تكون بين 0 و 100%.',
        'x_qty.required'             => 'قيمة X مطلوبة في عرض BxGy.',
        'y_qty.required'             => 'قيمة Y مطلوبة في عرض BxGy.',
        'bundle_price.required'      => 'سعر الحزمة مطلوب في عرض Bundle.',
        'end_at.after_or_equal'      => 'تاريخ النهاية يجب أن يكون بعد أو يساوي تاريخ البداية.',
        'hours_from.date_format'     => 'وقت البداية غير صحيح (HH:MM).',
        'hours_to.date_format'       => 'وقت النهاية غير صحيح (HH:MM).',
        'priority.min'               => 'الأولوية يجب ألا تقل عن 1.',
    ];

    /* أسماء الحقول لرسائل أوضح */
    protected $validationAttributes = [
        'name.ar'                  => 'الاسم (عربي)',
        'name.en'                  => 'الاسم (إنجليزي)',
        'discount_value'           => 'قيمة الخصم',
        'x_qty'                    => 'كمية X',
        'y_qty'                    => 'كمية Y',
        'bundle_price'             => 'سعر الحزمة',
        'max_discount_per_order'   => 'الحد الأقصى للخصم لكل فاتورة',
        'start_at'                 => 'تاريخ البداية',
        'end_at'                   => 'تاريخ النهاية',
        'hours_from'               => 'ساعة البداية',
        'hours_to'                 => 'ساعة النهاية',
        'days_of_week'             => 'أيام الأسبوع',
    ];

    /* ==================== دورة الحياة ==================== */
    public function mount(?Offer $offer = null)
    {
        if ($offer && $offer->exists) {
            $this->offer = $offer;

            // تعبئة الحقـول البسيطة
            $this->fill($offer->only([
                'type','discount_value','x_qty','y_qty','bundle_price','max_discount_per_order',
                'is_stackable','priority','policy','status','start_at','end_at','days_of_week',
                'hours_from','hours_to','sales_channel'
            ]));

            // الترجمات
            $this->name        = $offer->getTranslations('name') ?: $this->name;
            $this->description = $offer->getTranslations('description') ?: $this->description;

            // العلاقات
            $this->branch_ids   = $offer->branches()->pluck('branches.id')->toArray();
            $this->product_ids  = $offer->products()->pluck('products.id')->toArray();
            $this->category_ids = $offer->categories()->pluck('categories.id')->toArray();
        }
    }

    /* تحديث فوري وتحقق للحقل المتغير + تصفير الحقول الخاصة بالأنواع */
    public function updated($name, $value)
    {
        // تحقق فوري للحقل
        $this->validateOnly($name);

        // ضبط الحقول حسب نوع العرض
        if ($name === 'type') {
            if (in_array($this->type, ['percentage','fixed'])) {
                $this->x_qty = $this->y_qty = $this->bundle_price = null;
            } elseif ($this->type === 'bxgy') {
                $this->discount_value = $this->bundle_price = null;
            } elseif ($this->type === 'bundle') {
                $this->discount_value = $this->x_qty = $this->y_qty = null;
            }
        }

        // توحيد أيام الأسبوع = أرقام صحيحة فريدة 1..7
        if ($name === 'days_of_week') {
            $this->days_of_week = array_values(array_unique(
                array_filter(array_map('intval', (array) $this->days_of_week), fn($d) => $d >= 1 && $d <= 7)
            ));
        }
    }

    /* ==================== حفظ ==================== */
    public function save()
    {
        // تحقق أساسي
        $data = $this->validate();

        // تحقق إضافي لساعات اليوم (لأن after/before لا يعمل على time)
        if ($this->hours_from && $this->hours_to && $this->hours_to < $this->hours_from) {
            $this->addError('hours_to', 'وقت النهاية يجب أن يكون بعد وقت البداية.');
            return;
        }

        // تحضير المدخلات
        $attrs = array_merge($data, [
            'name'        => $this->name,
            'description' => $this->description,
        ]);

        // إنشاء/تحديث
        if ($this->offer) {
            $this->offer->update($attrs);
            $offer = $this->offer->refresh();
        } else {
            $offer = Offer::create($attrs);
            $this->offer = $offer;
        }

        // مزامنة العلاقات
        $offer->branches()->sync($this->branch_ids);
        $offer->products()->sync(
            collect($this->product_ids)
                ->mapWithKeys(fn($id) => [(int)$id => ['min_qty' => 1]])
                ->all()
        );
        $offer->categories()->sync($this->category_ids);

        session()->flash('success', __('pos.msg_saved_ok'));
        return redirect()->route('offers.index');
    }

    /* ==================== تحذيرات ديناميكية (تظهر فورًا) ==================== */
    public function getWarningsProperty(): array
    {
        $warn = [];

        // نسبة الخصم > 100
        if ($this->type === 'percentage' && is_numeric($this->discount_value) && $this->discount_value > 100) {
            $warn[] = 'تنبيه: لا يُنصح بتحديد نسبة خصم تتجاوز 100%.';
        }

        // خصم ثابت وسالب
        if ($this->type === 'fixed' && is_numeric($this->discount_value) && $this->discount_value < 0) {
            $warn[] = 'تنبيه: قيمة الخصم الثابت يجب أن تكون موجبة.';
        }

        // ساعات يومية غير صحيحة
        if ($this->hours_from && $this->hours_to && $this->hours_to < $this->hours_from) {
            $warn[] = 'تحذير: وقت النهاية أقل من وقت البداية.';
        }

        // نطاق زمني غير محدد + مفتوح
        if (!$this->start_at && !$this->end_at) {
            $warn[] = 'معلومة: عدم تحديد فترة زمنية يجعل العرض مفتوح المدة.';
        }

        // نطاق التطبيق واسع جدًا
        if (empty($this->branch_ids) && empty($this->product_ids) && empty($this->category_ids)) {
            $warn[] = 'ملاحظة: لم يتم تحديد فروع/منتجات/فئات — سيتم تطبيق العرض على الكل.';
        }

        // سياسة ودمج غير متناسقين
        if (!$this->is_stackable && $this->policy === 'largest_discount') {
            $warn[] = 'تنبيه: عند إيقاف الدمج لن يكون لسياسة "الخصم الأكبر" أي تأثير.';
        }

        // BxGy بدون قيم
        if ($this->type === 'bxgy' && (empty($this->x_qty) || empty($this->y_qty))) {
            $warn[] = 'تحذير: يجب تحديد قيم X و Y لعرض BxGy.';
        }

        // Bundle بدون سعر
        if ($this->type === 'bundle' && (is_null($this->bundle_price) || $this->bundle_price === '')) {
            $warn[] = 'تحذير: يجب تحديد سعر الحزمة لعرض Bundle.';
        }

        // حد خصم لكل فاتورة عالي جدًا (تذكير)
        if (is_numeric($this->max_discount_per_order) && $this->max_discount_per_order > 0) {
            $warn[] = 'معلومة: تم تفعيل حد أقصى للخصم على كل فاتورة.';
        }

        return $warn;
    }

    /* ==================== العرض ==================== */
    public function render()
    {
        // يمكنك تمرير التحذيرات للعرض عند الحاجة: ['warnings' => $this->warnings]
        return view('livewire.offers.manage');
    }
}
