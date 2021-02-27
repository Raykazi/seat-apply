<?PHP
return [
	'application' => [
		'name' => 'Applications',
		'icon' => 'fab fa-wpforms',
		'route_segment' => 'application',
		'permission' => 'application.apply',
		'entries' => [
			[
				'name' => 'Apply',
				'icon' => 'fas fa-user-plus',
				'route' => 'application.request',
				'permission' => 'application.apply',
			],
			[
				'name' => 'Applications',
				'icon' => 'fas fa-user-tag',
				'route' => 'application.list',
				'permission' => 'application.recruiter',
			],
            [
                'name' => 'Questions',
                'icon' => 'fas fa-question',
                'route' => 'application.questions',
                'permission' => 'application.director',
            ],
			[
                'name' => 'About',
                'icon' => 'fas fa-dumpster-fire',
                'route' => 'application.about',
                'permission' => 'application.apply',
            ],
		],
	],
];
