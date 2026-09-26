<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        // قوائم أسماء عربية واقعية (مذكر + مؤنث)
        $maleNames = [
            'أحمد محمد العتيبي', 'خالد عبدالله القحطاني', 'محمد سعد الدوسري',
            'عبدالرحمن ناصر الشمري', 'سلطان فهد المطيري', 'يوسف علي الحربي',
            'عمر إبراهيم الزهراني', 'فهد سلمان العنزي', 'بندر تركي السبيعي',
            'ماجد حسن الغامدي', 'طلال ماجد الرشيد', 'نواف سعود البقمي',
            'عبدالعزيز فيصل المالكي', 'راكان مشعل العسيري', 'سامي أحمد الجهني',
            'زياد منصور الشهري', 'تركي بندر الفضلي', 'حاتم وليد الأحمدي',
            'فيصل خالد الرشودي', 'مشاري نايف الهاجري',
        ];

        $femaleNames = [
            'نورة محمد العتيبي', 'سارة عبدالله القحطاني', 'ريم سعد الدوسري',
            'لمى ناصر الشمري', 'هند فهد المطيري', 'دانة علي الحربي',
            'أمل إبراهيم الزهراني', 'جواهر سلمان العنزي', 'منى تركي السبيعي',
            'رزان حسن الغامدي', 'غادة ماجد الرشيد', 'شهد سعود البقمي',
            'لينا فيصل المالكي', 'عبير مشعل العسيري', 'نجود أحمد الجهني',
        ];

        $allNames = array_merge($maleNames, $femaleNames);

        // إيميل عربي مقبول (يعتمد على أرقام لتجنب التكرار)
        $uniqueId = fake()->unique()->numerify('####');
        $emailDomains = ['lawfirm.test', 'example.sa', 'office.com', 'mail.sa'];
        $domain = fake()->randomElement($emailDomains);

        return [
            'name'      => fake()->randomElement($allNames),
            'email'     => "user{$uniqueId}@{$domain}",
            'password'  => Hash::make('password'),
            'phone'     => '05' . fake()->unique()->numerify('########'),
            'user_type' => fake()->randomElement(['admin', 'lawyer', 'client', 'staff']),
            'is_active' => true,
        ];
    }
/**
 * ربط الدور المناسب حسب نوع المستخدم (user_type)
 * بدلاً من استخدام دور عشوائي
 */
    public function configure(): static
    {
        return $this->afterCreating(function (User $user) {
            $roleMap = [
                'admin'  => 'مدير النظام',
                'lawyer' => 'محامي',
                'client' => 'موكل',
                'staff'  => 'موظف إداري',
            ];

            $roleName = $roleMap[$user->user_type] ?? null;
            if ($roleName) {
                $roleId = Role::where('role_name', $roleName)->value('id');
                if ($roleId) {
                    $user->roles()->syncWithoutDetaching([$roleId]);
                }
            }
        });
    }

    public function admin(): static
    {
        return $this->state(fn () => ['user_type' => 'admin']);
    }

    public function lawyer(): static
    {
        return $this->state(fn () => ['user_type' => 'lawyer']);
    }

    public function client(): static
    {
        return $this->state(fn () => ['user_type' => 'client']);
    }

    public function staff(): static
    {
        return $this->state(fn () => ['user_type' => 'staff']);
    }
}