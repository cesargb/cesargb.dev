<?php

namespace App\Data;

class Skills
{
    /**
     * Every technology I have worked with, grouped by area. `weight` goes from 1 (little
     * experience) to 5 (a lot). A `name` is either shown verbatim or, when it is an array,
     * translated with its `es` / `en` keys. Category names live in `lang/{locale}/experience.php`.
     */
    private static array $categories = [
        [
            'key' => 'aws',
            'color' => '#f5a524',
            'skills' => [
                ['name' => 'EC2', 'weight' => 5],
                ['name' => 'ECS', 'weight' => 5],
                ['name' => 'ELB', 'weight' => 4],
                ['name' => 'S3', 'weight' => 5],
                ['name' => 'RDS', 'weight' => 4],
                ['name' => 'Lambda', 'weight' => 5],
                ['name' => 'AWS Backup', 'weight' => 5],
                ['name' => 'CloudFront', 'weight' => 5],
                ['name' => 'Route 53', 'weight' => 5],
                ['name' => 'IAM', 'weight' => 4],
                ['name' => 'VPC', 'weight' => 5],
                ['name' => 'SNS', 'weight' => 5],
                ['name' => 'SQS', 'weight' => 5],
                ['name' => 'CloudFormation', 'weight' => 3],
                ['name' => 'SSM', 'weight' => 4],
                ['name' => 'CloudWatch', 'weight' => 4],
                ['name' => 'Application Load Balancer', 'weight' => 4],
                ['name' => 'WAF', 'weight' => 4],
            ],
        ],
        [
            'key' => 'linux',
            'color' => '#4ade80',
            'skills' => [
                ['name' => 'Linux', 'weight' => 5],
                ['name' => ['es' => 'Kernel de Linux', 'en' => 'Linux kernel'], 'weight' => 4],
                ['name' => 'Windows Server', 'weight' => 3],
                ['name' => 'Docker', 'weight' => 4],
                ['name' => ['es' => 'Monitorización', 'en' => 'Monitoring'], 'weight' => 4],
                ['name' => 'Backups', 'weight' => 5],
                ['name' => ['es' => 'Recuperación ante desastres', 'en' => 'Disaster recovery'], 'weight' => 4],
                ['name' => 'Shell scripting', 'weight' => 4],
                ['name' => 'GitHub Actions', 'weight' => 4],
                ['name' => 'iptables', 'weight' => 4],
                ['name' => 'iproute2', 'weight' => 4],
                ['name' => 'VMware', 'weight' => 4],
                ['name' => 'Citrix', 'weight' => 3],
                ['name' => 'Avamar', 'weight' => 3],
                ['name' => 'Active Directory', 'weight' => 4],
            ],
        ],
        [
            'key' => 'programming',
            'color' => '#ff5f6d',
            'skills' => [
                ['name' => 'PHP', 'weight' => 5],
                ['name' => 'Laravel', 'weight' => 5],
                ['name' => 'JavaScript', 'weight' => 5],
                ['name' => 'Vue.js', 'weight' => 5],
                ['name' => 'Nuxt', 'weight' => 5],
                ['name' => 'Node.js', 'weight' => 5],
                ['name' => 'CSS / Sass', 'weight' => 5],
                ['name' => 'Tailwind CSS', 'weight' => 3],
                ['name' => 'MySQL', 'weight' => 5],
                ['name' => 'PostgreSQL', 'weight' => 3],
                ['name' => 'Redis', 'weight' => 5],
                ['name' => 'C', 'weight' => 4],
                ['name' => 'C#', 'weight' => 5],
                ['name' => 'Angular', 'weight' => 4],
                ['name' => 'Python', 'weight' => 4],
                ['name' => 'Rust', 'weight' => 2],
                ['name' => 'Go', 'weight' => 2],
            ],
        ],
        [
            'key' => 'services',
            'color' => '#60a5fa',
            'skills' => [
                ['name' => 'Hosting', 'weight' => 5],
                ['name' => 'DNS', 'weight' => 5],
                ['name' => ['es' => 'Correo', 'en' => 'Email'], 'weight' => 5],
                ['name' => 'SMTP', 'weight' => 5],
                ['name' => 'POP / IMAP', 'weight' => 5],
                ['name' => 'HTTP', 'weight' => 5],
                ['name' => 'FTP', 'weight' => 4],
                ['name' => 'VPN', 'weight' => 4],
                ['name' => 'IPsec', 'weight' => 4],
                ['name' => 'Firewalls', 'weight' => 5],
                ['name' => ['es' => 'Redes', 'en' => 'Networking'], 'weight' => 5],
                ['name' => 'Apache', 'weight' => 5],
                ['name' => 'Nginx', 'weight' => 5],
                ['name' => 'Caddy', 'weight' => 2],
                ['name' => 'Postfix', 'weight' => 5],
                ['name' => 'Dovecot', 'weight' => 4],
                ['name' => 'Sendmail', 'weight' => 5],
            ],
        ],
    ];

    public static function all(): array
    {
        return self::$categories;
    }

    /**
     * Categories with their names and skill names resolved for the given locale.
     *
     * @return array<int, array{key: string, name: string, color: string, skills: array<int, array{name: string, weight: int}>}>
     */
    public static function forLocale(string $locale): array
    {
        return array_map(fn (array $category) => [
            'key' => $category['key'],
            'name' => __('experience.category_'.$category['key'], locale: $locale),
            'color' => $category['color'],
            'skills' => array_map(fn (array $skill) => [
                'name' => is_array($skill['name']) ? $skill['name'][$locale] ?? $skill['name']['en'] : $skill['name'],
                'weight' => $skill['weight'],
            ], $category['skills']),
        ], self::$categories);
    }
}
