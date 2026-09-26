<?php

namespace Database\Factories;

use App\Models\CaseSession;
use App\Models\CourtCase;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CaseSessionFactory extends Factory
{
    protected $model = CaseSession::class;

    public function definition(): array
    {
        $notes = [
            'تم الاستماع إلى أقوال المدعي والمدعى عليه، وقررت المحكمة تأجيل الجلسة للاطلاع على المستندات.',
            'قدّم المحامي مذكرة دفاعه، وطلب من المحكمة منحه مهلة لتقديم المستندات المؤيدة.',
            'حضر شهود الطرفين وأدلوا بشهاداتهم، وقررت المحكمة حجز القضية للحكم.',
            'طلب المدعي إحالة القضية إلى خبير لفحص المستندات المتنازع عليها.',
            'تم تقديم تقرير الخبير ومناقشته من قبل الطرفين، مع تحديد جلسة للنطق بالحكم.',
            'حضر الطرفان وتمكنا من التوصل إلى تسوية جزئية، وأبقيت المحكمة القضية للنظر في الباقي.',
            'غاب المدعى عليه دون عذر مقبول، فقررت المحكمة تأجيل الجلسة لإعلانه مرة أخرى.',
            'تم الاستماع إلى مرافعة النيابة العامة، وقررت المحكمة حجز القضية للحكم.',
            'قررت المحكمة ضم قضية أخرى مرتبطة لاتحاد الموضوع والخصوم.',
            'أحالت المحكمة القضية إلى الدائرة المختصة بعد إقرار الدفع بعدم الاختصاص.',
        ];

        return [
            'session_date'       => fake()->dateTimeBetween('-6 months', '+1 month'),
            'session_notes'      => fake()->randomElement($notes),
            'next_session_date'  => fake()->optional(0.6)->dateTimeBetween('now', '+3 months')?->format('Y-m-d'),
            'cases_id'           => CourtCase::inRandomOrder()->value('id'),
            'users_id'           => User::where('user_type', 'lawyer')->inRandomOrder()->value('id'),
        ];
    }
}