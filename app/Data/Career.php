<?php

namespace App\Data;

class Career
{
    /**
     * Professional trajectory, most recent first. `to` is null for the current job.
     */
    private static array $jobs = [
        [
            'company' => 'Descom.es',
            'url' => 'https://www.descom.es',
            'from' => 2005,
            'to' => null,
            'role' => [
                'es' => 'Arquitecto de software y desarrollador',
                'en' => 'Software architect and developer',
            ],
            'summary' => [
                'es' => 'Diseño, desarrollo y opero aplicaciones web de principio a fin, desde la definición técnica hasta producción.',
                'en' => 'I design, build and run web applications end to end, from the technical definition to production.',
            ],
            'highlights' => [
                'es' => [
                    'Backends con Laravel y PHP; interfaces con Vue.js y Nuxt.',
                    'Arquitectura cloud en AWS: EC2, ECS, ELB, S3, RDS, Lambda y AWS Backup.',
                    'Servidores Linux y Windows, redes y firewalls, monitorización, backups y recuperación ante desastres.',
                ],
                'en' => [
                    'Backends with Laravel and PHP; interfaces with Vue.js and Nuxt.',
                    'Cloud architecture on AWS: EC2, ECS, ELB, S3, RDS, Lambda and AWS Backup.',
                    'Linux and Windows servers, networks and firewalls, monitoring, backups and disaster recovery.',
                ],
            ],
        ],
        [
            'company' => 'Electronic Data Systems (EDS)',
            'url' => null,
            'from' => 2003,
            'to' => 2005,
            'role' => [
                'es' => 'Administrador de redes y sistemas',
                'en' => 'Network and systems administrator',
            ],
            'summary' => [
                'es' => 'Infraestructura corporativa de más de 450 equipos repartidos en dos centros de trabajo.',
                'en' => 'Corporate infrastructure of more than 450 machines across two sites.',
            ],
            'highlights' => [
                'es' => [
                    'Redes, conexión entre sedes y VPN, con monitorización y alertas.',
                    'Servidores web, de correo (SMTP, POP, IMAP) y de SMS para el Call Center.',
                    'Herramientas internas, como un plano digital de los centros con el estado de equipos y agentes en tiempo real.',
                ],
                'en' => [
                    'Networks, site-to-site links and VPN, with monitoring and alerts.',
                    'Web, mail (SMTP, POP, IMAP) and SMS servers for the call center.',
                    'Internal tools, such as a digital floor plan showing the live status of machines and agents.',
                ],
            ],
        ],
        [
            'company' => 'Wanadoo España',
            'url' => null,
            'from' => 1999,
            'to' => 2003,
            'role' => [
                'es' => 'Desarrollador',
                'en' => 'Developer',
            ],
            'summary' => [
                'es' => 'Software de gestión operativa y análisis de datos para entornos empresariales.',
                'en' => 'Operations management and data analysis software for business environments.',
            ],
            'highlights' => [
                'es' => [
                    'Aplicación de campañas de llamadas salientes, con herramientas para agentes e informes.',
                    'Software de análisis y control de facturación.',
                    'Herramientas de administración y soporte remoto de equipos Windows.',
                ],
                'en' => [
                    'Outbound call campaign application, with agent tools and reporting.',
                    'Billing analysis and control software.',
                    'Administration and remote support tools for Windows machines.',
                ],
            ],
        ],
    ];

    public static function all(): array
    {
        return self::$jobs;
    }

    /**
     * Year the professional trajectory started (the oldest job).
     */
    public static function startYear(): int
    {
        return min(array_column(self::$jobs, 'from'));
    }
}
