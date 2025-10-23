<?php

namespace App\Http\Livewire\coupons;

use Livewire\Component;
use App\models\offers\coupons as Coupon;

class Manage extends Component
{
    public ?Coupon $coupon = null;

    // حقول مترجمة
    public $name = ['ar'=>'','en'=>''];
    public $description = ['ar'=>'','en'=>''];

    // خصائص الكوبون
    public $type = 'percentage';            // percentage | fixed
    public $discount_value = null;

    public $max_uses_per_customer = 1;
    public $max_total_uses = null;
    public $is_stackable = false;

    public $status = 'active';              // active|paused|expired
    public $start_at = null;
    public $end_at = null;

    // نطاق التطبيق
    public $branch_ids = [];
    public $customer_ids = [];

    /* ==================== قواعد التحقق ==================== */
    protected function rules()
    {
        $base = [
            'name.ar'                 => 'required|string|max:255',
            'name.en'                 => 'required|string|max:255',
            'description.ar'          => 'nullable|string|max:2000',
            'description.en'          => 'nullable|string|max:2000',

            'type'                    => 'required|in:percentage,fixed',
            'is_stackable'            => 'boolean',
            'status'                  => 'required|in:active,paused,expired',

            'start_at'                => 'nullable|date',
            'end_at'                  => 'nullable|date|after_or_equal:start_at',

            'max_uses_per_customer'   => 'required|integer|min:1',
            'max_total_uses'          => 'nullable|integer|min:1',

            'branch_ids'              => 'array',
            'branch_ids.*'            => 'integer',
            'customer_ids'            => 'array',
            'customer_ids.*'          => 'integer',
        ];

        // القاعدة الديناميكية لقيمة الخصم
        $base['discount_value'] = $this->type === 'percentage'
            ? 'required|numeric|min:0|max:100'
            : 'required|numeric|min:0';

        return $base;
    }

    /* ==================== رسائل عربية محسّنة ==================== */
    protected $messages = [
        'name.ar.required'              => 'الاسم بالعربية مطلوب.',
        'name.en.required'              => 'الاسم بالإنجليزية مطلوب.',
        'name.*.max'                    => 'الاسم يجب ألا يتجاوز 255 حرفًا.',
        'discount_value.required'       => 'قيمة الخصم مطلوبة.',
        'discount_value.max'            => 'قيمة نسبة الخصم يجب ألا تتجاوز 100%.',
        'max_uses_per_customer.required'=> 'حد استخدام العميل مطلوب.',
        'max_uses_per_customer.min'     => 'الحد الأدنى لاستخدام العميل مرة واحدة على الأقل.',
        'max_total_uses.min'            => 'إجمالي مرات الاستخدام يجب أن يكون رقمًا موجبًا.',
        'end_at.after_or_equal'         => 'تاريخ النهاية يجب أن يكون بعد أو يساوي تاريخ البداية.',
        'status.in'                     => 'حالة الكوبون غير صحيحة.',
        'type.in'                       => 'نوع الخصم غير صحيح.',
        'branch_ids.array'              => 'تنسيق الفروع غير صحيح.',
        'customer_ids.array'            => 'تنسيق العملاء غير صحيح.',
    ];

    /* أسماء أوضح في رسائل الأخطاء */
    protected $validationAttributes = [
        'name.ar'                 => 'الاسم (عربي)',
        'name.en'                 => 'الاسم (إنجليزي)',
        'discount_value'          => 'قيمة الخصم',
        'max_uses_per_customer'   => 'حد الاستخدام لكل عميل',
        'max_total_uses'          => 'إجمالي مرات الاستخدام',
        'start_at'                => 'تاريخ البداية',
        'end_at'                  => 'تاريخ النهاية',
        'branch_ids'              => 'الفروع',
        'customer_ids'            => 'العملاء',
    ];

    /* ==================== دورة الحياة ==================== */
    public function mount(?Coupon $coupon = null)
    {
        if ($coupon && $coupon->exists) {
            $this->coupon = $coupon;

            $this->fill($coupon->only([
                'type','discount_value','max_uses_per_customer','max_total_uses',
                'is_stackable','status','start_at','end_at'
            ]));

            $this->name        = $coupon->getTranslations('name') ?: $this->name;
            $this->description = $coupon->getTranslations('description') ?: $this->description;

            $this->branch_ids   = $coupon->branches()->pluck('branches.id')->map(fn($v)=>(int)$v)->unique()->values()->all();
            $this->customer_ids = $coupon->customers()->pluck('customers.id')->map(fn($v)=>(int)$v)->unique()->values()->all();
        }
    }

    /* تحقق فوري للحقل + ضبط عند تغيير النوع */
    public function updated($name, $value)
    {
        $this->validateOnly($name);

        // تنظيف المصفوفات لتكون أرقام فريدة
        if (in_array($name, ['branch_ids','customer_ids'], true)) {
            $this->$name = array_values(array_unique(array_map('intval', (array)$this->$name)));
        }

        // لو النوع نسبة؛ قصّ القيمة على 100 كحد أقصى (تجميلي)
        if ($name === 'discount_value' && $this->type === 'percentage' && is_numeric($this->discount_value)) {
            $this->discount_value = min(100, max(0, (float)$this->discount_value));
        }

        // عند تغيير النوع؛ تأكد من ملاءمة قيمة الخصم
        if ($name === 'type') {
            if ($this->type === 'percentage' && is_numeric($this->discount_value) && $this->discount_value > 100) {
                $this->discount_value = 100;
            }
        }
    }

    /* ==================== حفظ ==================== */
    public function save()
    {
        $data = $this->validate();

        // تحقق إضافي: لا يجوز أن يكون حد العميل أكبر من الإجمالي (إن كان محددًا)
        if (!is_null($this->max_total_uses) && $this->max_uses_per_customer > $this->max_total_uses) {
            $this->addError('max_uses_per_customer', 'لا يمكن أن يتجاوز حد استخدام العميل إجمالي مرات الاستخدام.');
            return;
        }

        $attrs = array_merge($data, [
            'name'        => $this->name,
            'description' => $this->description,
        ]);

        if ($this->coupon) {
            $this->coupon->update($attrs);
            $coupon = $this->coupon->refresh();
        } else {
            $coupon = Coupon::create($attrs);
            $this->coupon = $coupon;
        }

        $coupon->branches()->sync($this->branch_ids);
        $coupon->customers()->sync($this->customer_ids);

        session()->flash('success', __('pos.msg_saved_ok'));
        return redirect()->route('coupons.index');
    }

    /* ==================== تحذيرات ديناميكية للواجهة ==================== */
    public function getWarningsProperty(): array
    {
        $warn = [];

        // نسب/قيم غير منطقية
        if ($this->type === 'percentage' && is_numeric($this->discount_value) && $this->discount_value > 90) {
            $warn[] = 'تنبيه: نسبة الخصم أعلى من 90% وقد تؤثر على هامش الربح.';
        }
        if ($this->type === 'fixed' && is_numeric($this->discount_value) && $this->discount_value == 0) {
            $warn[] = 'ملاحظة: خصم بقيمة 0 لن يغير سعر الفاتورة.';
        }

        // مدة غير محددة
        if (!$this->start_at && !$this->end_at) {
            $warn[] = 'معلومة: لم يتم تحديد فترة صلاحية — الكوبون سيكون مفتوح المدة حتى تغييره يدويًا.';
        }

        // نطاق التطبيق واسع
        if (empty($this->branch_ids)) {
            $warn[] = 'ملاحظة: لم يتم تحديد فروع — سيتم السماح باستخدام الكوبون في جميع الفروع.';
        }
        if (empty($this->customer_ids)) {
            $warn[] = 'ملاحظة: لم يتم تقييد الكوبون بعملاء محددين — سيكون متاحًا لجميع العملاء.';
        }

        // الدمج
        if (!$this->is_stackable) {
            $warn[] = 'تنبيه: هذا الكوبون غير قابل للدمج وسيعطّل باقي العروض/الكوبونات عند تطبيقه.';
        }

        // حدود الاستخدام
        if (is_null($this->max_total_uses)) {
            $warn[] = 'معلومة: إجمالي مرات الاستخدام غير محدد (غير محدود).';
        } elseif ($this->max_total_uses <= 10) {
            $warn[] = 'تذكير: إجمالي مرات الاستخدام منخفض (≤10).';
        }

        // اتساق الحالة والزمن
        if ($this->status === 'active' && $this->end_at && now()->gt($this->end_at)) {
            $warn[] = 'تحذير: الحالة نشطة لكن تاريخ النهاية قد مضى — سيُعتبر الكوبون منتهي الصلاحية فعليًا.';
        }

        // اتساق حدود الاستخدام
        if (!is_null($this->max_total_uses) && $this->max_uses_per_customer > $this->max_total_uses) {
            $warn[] = 'تحذير: حد العميل أكبر من الإجمالي — لن يتمكن عميل واحد من تخطي الإجمالي.';
        }

        return $warn;
    }

    /* ==================== العرض ==================== */
    public function render()
    {
        // اعرض التحذيرات في الـ Blade عبر $this->warnings (مثال نفس صفحة العروض)
        return view('livewire.coupons.manage');
    }
}
